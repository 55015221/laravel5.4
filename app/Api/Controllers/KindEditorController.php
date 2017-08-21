<?php
/**
 * KindEditor编辑器接口
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;

use App\Api\Requests\KindEditorSystemRequest;
use App\Api\Requests\KindEditorUploadRequest;
use Storage;

class KindEditorController extends BaseController
{
    /**
     * auth
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * 排序方式
     *
     * @var string
     */
    public static $orderBy = 'size';

    /**
     * 定义允许上传的文件扩展名
     *
     * @var array
     */
    protected $extName = [
        'image' => ['gif', 'jpg', 'jpeg', 'png', 'bmp'],
        'flash' => ['swf', 'flv'],
        'media' => ['swf', 'flv', 'mp3', 'mp4', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'],
        'file' => ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2', 'pdf'],
    ];

    /**
     * 上传
     *
     * @param KindEditorUploadRequest $request
     * @return array
     */
    public function upload(KindEditorUploadRequest $request)
    {
        if (!$request->files->has('file')) {
            return $this->responseError(ERROR_VALIDATION_FAILED, '未上传文件');
        }
        $file = $request->file('file');
        $fileType = $request->input('dir', 'file');
        if (!in_array(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION), $this->extName[$fileType])) {
            return $this->responseError(ERROR_VALIDATION_FAILED, '上传文件扩展名是不允许的扩展名');
        }
        $fileType = $fileType == 'image' ? 'images' : 'attachments';
        $username = $request->user($this->guard)->username;
        //本地存储路径为：app/upload/images/用户名
        $dir = [
            'app',
            'upload',
            $fileType,
            $username,
        ];
        $dir = storage_path(implode(DIRECTORY_SEPARATOR, $dir));

        if (!is_dir($dir)) {
            if (!mkdir($dir)) {
                $this->responseError(ERROR_UNKNOWN, '创建目录失败');
            }
        }

        $mimeType = '';
        if ($fileType == 'images') {
            //使用文件md5哈希值作为文件名
            $filename = md5_file($file->path()) . '.' . strtolower($file->getClientOriginalExtension());
            $path = $dir . DIRECTORY_SEPARATOR . $filename;
            $mimeType = $file->getMimeType();
        } else {
            //使用原始文件名
            $filename = strtolower($file->getClientOriginalName());

            //检查是否已经存在同名文件，并且使用md5检查文件是否完全相同
            //如果没有同名文件，则使用原始文件名保存，否则，在原始文件名后加上一个数字后缀保存
            $path = $dir . DIRECTORY_SEPARATOR . $filename;
            if (file_exists($path)) {
                $oldFileHash = md5_file($path);
                $newFileHash = md5_file($file->path());
                if ($newFileHash != $oldFileHash) {
                    $path = $this->getAvailableFilename($path);
                    $fileInfo = pathinfo($path);
                    $filename = $fileInfo['basename'];
                }
            }
        }

        $file->move($dir, $filename);

        //把jpg图片压缩为75%质量
        if ($mimeType == 'image/jpeg') {
            \Image::make($path)->encode('jpg', 75)->save($path);
        }

        //上传到七牛
        $disk = Storage::disk('qiniu');
        $qiniuFilename = "{$fileType}/{$username}/{$filename}";
        $fileContent = file_get_contents($path);
        $disk->put($qiniuFilename, $fileContent);

        $url = 'http://' . env('QINIU_DOMAIN') . '/' . $qiniuFilename;

        return $this->responseData(['url' => $url]);
    }

    /**
     * 文件空间
     *
     * @param KindEditorSystemRequest $request
     * @return string
     */
    public function system(KindEditorSystemRequest $request)
    {
        $username = $request->user($this->guard)->username;
        //目录名
        $dirName = empty($request->input('dir')) ? '' : trim($request->input('dir'));
        if (!in_array($dirName, array('', 'image', 'flash', 'media', 'file'))) {
            return $this->responseError(ERROR_UNKNOWN, 'Invalid Directory name');
        }
        $dirName = $dirName == 'image' ? 'images' : 'attachments';

        $dir = storage_path('app' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $dirName . DIRECTORY_SEPARATOR . $username);

        $rootPath = $dir . DIRECTORY_SEPARATOR;
        $extArr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');
        $rootUrl = 'http://' . env('QINIU_DOMAIN') . "/{$dirName}/{$username}/";

        //根据path参数，设置各路径和URL
        if (empty($request->input('path'))) {
            $currentPath = realpath($rootPath) . DIRECTORY_SEPARATOR;
            $currentUrl = $rootUrl;
            $currentDirPath = '';
            $moveUpDirPath = '';
        } else {
            $currentPath = realpath($rootPath) . DIRECTORY_SEPARATOR . $request->input('path');
            $currentUrl = $rootUrl . $request->input('path');
            $currentDirPath = $request->input('path');
            $moveUpDirPath = preg_replace('/(.*?)[^\/]+\/$/', '$1', $currentDirPath);
        }
        $realPath = realpath($rootPath);
        //排序形式，name or size or type
        self::$orderBy = empty($request->input('order')) ? 'name' : strtolower($request->input('order'));

        //不允许使用..移动到上一级目录
        if (preg_match('/\.\./', $currentPath)) {
            return $this->responseError(ERROR_UNKNOWN, $realPath . 'Access is not allowed');
        }
        //最后一个字符不是/
        if (!preg_match('/\/$/', $currentPath)) {
            return $this->responseError(ERROR_UNKNOWN, 'Parameter is not valid');
        }
        //目录不存在或不是目录
        if (!file_exists($currentPath) || !is_dir($currentPath)) {
            return $this->responseError(ERROR_UNKNOWN, $realPath . 'Directory does not exist');
        }

        //遍历目录取得文件信息
        $fileList = array();
        if ($handle = opendir($currentPath)) {
            $i = 0;
            while (false !== ($filename = readdir($handle))) {
                if ($filename{0} == '.') {
                    continue;
                }
                $file = $currentPath . $filename;
                if (is_dir($file)) {
                    $fileList[$i]['is_dir'] = true;
                    $fileList[$i]['has_file'] = (count(scandir($file)) > 2);
                    $fileList[$i]['filesize'] = 0;
                    $fileList[$i]['is_photo'] = false;
                    $fileList[$i]['filetype'] = '';
                } else {
                    $fileList[$i]['is_dir'] = false;
                    $fileList[$i]['has_file'] = false;
                    $fileList[$i]['filesize'] = filesize($file);
                    $fileList[$i]['dir_path'] = '';
                    $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $fileList[$i]['is_photo'] = in_array($fileExt, $extArr);
                    $fileList[$i]['filetype'] = $fileExt;
                }
                $fileList[$i]['filename'] = $filename;
                $fileList[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file));
                $i++;
            }
            closedir($handle);
        }
        // 排序
        usort($fileList, 'self::sortFile');
        // 文件系统
        $fileSystem = array();
        $fileSystem['moveup_dir_path'] = $moveUpDirPath;
        $fileSystem['current_dir_path'] = $currentDirPath;
        $fileSystem['current_url'] = $currentUrl;
        $fileSystem['total_count'] = count($fileList);
        $fileSystem['file_list'] = $fileList;
        return $this->responseData($fileSystem);
    }

    /**
     * 文件排序
     *
     * @param $a
     * @param $b
     * @return int
     */
    protected static function sortFile($a, $b)
    {
        if ($a['is_dir'] && !$b['is_dir']) {
            return -1;
        }

        if (!$a['is_dir'] && $b['is_dir']) {
            return 1;
        }

        if (self::$orderBy != 'size') {
            $key = 'file' . self::$orderBy;
            return strcmp($a[$key], $b[$key]);
        }

        if ($a['filesize'] > $b['filesize']) {
            return 1;
        } else if ($a['filesize'] < $b['filesize']) {
            return -1;
        } else {
            return 0;
        }
    }

    /**
     * 获取一个可用的文件名
     *
     * 本方法在$path文件所在的相同目录下生成一个可用的文件名，
     * 规则是在原来的文件名后面加上一个后缀
     *
     * @param string $path 文件名
     * @return string
     */
    protected function getAvailableFilename($path)
    {
        $fileInfo = pathinfo($path);
        $dirname = $fileInfo['dirname'];
        $filename = $fileInfo['filename'];
        $extension = $fileInfo['extension'];
        $filePath = $dirname . DIRECTORY_SEPARATOR . $filename . '_' . mt_rand(1, 9999) . $extension;

        if (file_exists($filePath)) {
            return $this->getAvailableFilename($path);
        }

        return $filePath;
    }
}
