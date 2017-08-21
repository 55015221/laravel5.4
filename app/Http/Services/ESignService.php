<?php
/**
 * E签宝 服务接口
 * @author 郭正
 * @date 2017-6-27
 * 使用方法
 * $contractPath = realpath(RUNTIME_PATH) . '/Temp';
 * $config = require_once(MODULE_PATH . 'Conf/eSign.php');
 * $contractFile = $contractPath . '/Temp/244.pdf'; //合同源文件
 *
 * $api = new ESignService($config);   //初始化
 * $api->setContractFile($contractFile);   //设置原始合同
 * $api->setContractNumber('XXXXXXX'); //设置合同编号
 * $api->setTempContractFilePath($contractPath);   //设置临时文件路径
 * $api->setUserId(11);
 * $contractId = $api->run();   //签章
 * dump($contractId);
 */

namespace App\Http\Services;

use App\Libs\tech\tech;
use Common\Model\ESignModel;
use \Exception;
use Think\Log;
use Vendor\ESign\ESignOpenAPI;

class ESignService
{
    /**
     * 合同编号
     * @var string
     */
    private $contractNumber;

    /**
     * 合同源文件
     * @var string
     */
    private $contractFile;

    /**
     * 临时合同文件路径
     * @var
     */
    private $tempContractFilePath;

    /**
     * 要使用的平台印章ID
     * 需要去e签宝后台配置 平台签章
     * @var int
     */
    public $sealId = 0;

    /**
     * 签章类型 单页 多页 骑缝 关键词
     * @var string
     * @see SignType
     */
    public $signType = 'Multi';

    /**
     * 用户签章位置
     * @var array
     */
    public $userSignPos = [
        'posPage' => 1,
        'posX'    => 400,
        'posY'    => 100,
        'key'     => '',
        'width'   => '',
    ];

    /**
     * 平台签章位置
     * @var
     */
    public $selfSignPos = [
        'posPage' => 1,
        'posX'    => 200,
        'posY'    => 100,
        'key'     => '',
        'width'   => '120',
    ];

    /**
     * 电子签章类型
     * @var string
     * @see PersonTemplateType
     */
    public $templateType = 'rectangle';

    /**
     * 电子签章颜色
     * @var string
     * @see SealColor
     */
    public $color = 'red';


    /**
     * ESignService constructor.
     * @param array $config 配置信息
     */
    public function __construct($config = [])
    {
        if (empty($config)) {
            $config = config('esign');
        }
        $this->config = $config;
    }

    /**
     * 获取e签宝 实例
     * @return \tech\core\eSign
     */
    private function getInstance()
    {
        return tech::getInstance($this->config);
    }

    /**
     * 设置签章合同源文件
     * @param string $contractFile 合同源文件
     */
    public function setContractFile($contractFile)
    {
        $this->contractFile = $contractFile;
    }

    /**
     * 获取签章合同源文件
     * @return string
     */
    private function getContractFile()
    {
        return $this->contractFile;
    }

    /**
     * 设置合同编号
     * @param $contractNumber
     */
    public function setContractNumber($contractNumber)
    {
        $this->contractNumber = $contractNumber;
    }

    /**
     * 获取合同编号
     * @return string
     */
    private function getContractNumber()
    {
        if (!empty($this->contractNumber)) {
            return $this->contractNumber;
        }
        return '';
    }

    /**
     * 获取用户印章 如果已经存在印章 不会重复调用接口
     * @param $userId
     * @param $update 更新
     * @return mixed|string
     */
    public function getUserSeal($userId, $update = false)
    {
        try {
            /* @var $ESignModel ESignModel */
            $ESignModel = D('ESign');
            if (!$update) {
                $account = $ESignModel->getAccountByUserId($userId);
                $accountId = $account['account_id'];
            }
            if (empty($accountId)) {
                $userInfo = $ESignModel->getUserInfoById($userId);
                $addPersonAccount = $this->addPersonAccount($userInfo['mobile'], $userInfo['name'], $userInfo['idNo'], $userInfo['personArea'], $userInfo['email'], $userInfo['organ'], $userInfo['title'], $userInfo['address']);
                $accountId = $addPersonAccount['accountId'];
                if ($update) {
                    $ESignModel->updateAccount($userId, $accountId);
                } else {
                    $ESignModel->addAccount($userId, $accountId);
                }
            }

            //第二步
            $signatureData = $ESignModel->getSignatureData($account['signature_path']);
            if (empty($signatureData)) {
                $addPersonTemplateSeal = $this->addPersonTemplateSeal($accountId);
                $signatureData = $addPersonTemplateSeal['imageBase64'];
                $ESignModel->saveAccountSignature($userId, base64_decode($signatureData));
            }
            return $ESignModel->getAccountByUserId($userId);
        } catch (Exception $e) {
            $error = json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            logger('【ESign getUserSeal Error】 ' . $error);
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * example
     * $data = [
     * [
     * 'accountId' => 'BEC93543502B4F35930899C66DBD7205',
     * 'seal'      => 'group1/M00/00/09/wKgBpVl-5S-AIkaKAAAJ1xHElgo165.png',
     * 'signPos' => [
     * 'key'       => '卡卡990',
     * ]
     * ],
     * [
     * 'sealId' => 0,
     * 'signPos' => [
     * 'key'       => '担保人（盖章）',
     * ]
     * ]
     * ];
     * $eSignService = new ESignService();
     * $eSignService->signType = 'Key';
     * $eSignService->stamp($file,$data);
     *
     * 合同盖章
     * @param string $contractFile 合同文件
     * @param array $data 盖章参数
     * @return int 合同ID
     * @throws Exception
     */
    public function stamp($contractFile, $data)
    {
        try {
            $total = count($data);
            $i = 1;
            $signServiceId = array_reduce($data, function ($signServiceId, $value) use (&$contractFile, &$i, $total) {
                //用户盖章
                if (isset($value['accountId']) && !empty($value['accountId'])) {
                    if (!isset($value['sealData']) || empty($value['sealData'])) {
                        $value['sealData'] = base64_encode(file_get_contents(ROOT_PATH . '/' . $value['seal']));
                    }
                    $userSignPDF = $this->userSignPDF($value['accountId'], $value['sealData'], $contractFile, $value['signPos']);
                    $signServiceId = $userSignPDF['signServiceId']; //合同电子档ID
                    $contractData = $userSignPDF['stream'];  //二进制合同
                }
                //平台盖章
                if (isset($value['sealId'])) {
                    $selfSignPDF = $this->selfSignPDF($contractFile, $contractFile, $value['sealId'], $value['signPos']);
                    $signServiceId = $selfSignPDF['signServiceId']; //合同电子档ID
                    $contractData = $selfSignPDF['stream']; //二进制合同
                }
                $message = "本次签署共{$total}次,成功{$i}次,本次的合同电子档ID：{$signServiceId}";
                logger('【ESign stamp Notice】 ' . $message);
                $i++;
                return $signServiceId;
            }, 0);

            /* @var $ESignModel ESignModel */
//            $ESignModel = D('ESign');
//            $contractId = $ESignModel->saveAccountContract(1, 'xxx_'.date('YmdHis'), $signServiceId, $contractFile);
        } catch (Exception $e) {
            $error = json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            logger('【ESign stamp Error】 ' . $error);
            logger('【ESign stamp Error】 $contractFile:' . $contractFile . PHP_EOL . '$data:' . var_export($data) . PHP_EOL);
            throw new Exception($e);
        }
    }


    /**
     *
     * 用户盖章
     * @param int $userId 用户ID
     * @param string $contractFile 合同文件
     * @return mixed 返回合同信息
     * @throws Exception
     */
    public function userStamp($accountId, $sealData, $dstPdfFile, $userSignPos = [])
    {
        try {
            $userSignPDF = $this->userSignPDF($accountId, $sealData, $dstPdfFile, $userSignPos);

            $contractData = base64_decode($userSignPDF['stream']);
            file_put_contents($dstPdfFile, $contractData);
            return $dstPdfFile;
        } catch (Exception $e) {
            $error = json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            logger('【ESign userStamp Error】 ' . $error);
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    /**
     * 平台盖章
     * @param string $contractFile 合同文件
     * @param int $sealId 平台印章ID 需在e签宝后台配置
     * @param array $selfSignPos 签章位置
     * @return mixed 合同文件路径
     * @throws Exception
     */
    public function platformStamp($contractFile, $sealId = 0, $selfSignPos = [])
    {
        try {
            $selfSignPDF = $this->selfSignPDF($contractFile, $contractFile, $sealId, $selfSignPos);
            $contractSignServiceId = $selfSignPDF['signServiceId']; //合同电子档ID
            $contractData = base64_decode($selfSignPDF['stream']);
            file_put_contents($contractFile, $contractData);
            return $contractFile;
        } catch (Exception $e) {
            $error = json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            logger('【ESign platformStamp Error】 ' . $error);
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 快捷签 入口 签章完成 返回合同ID
     * 此方法用户无感知 一次性完成所有步骤
     * @return mixed
     * @throws Exception
     */
    public function run($userId)
    {
        try {
            /* @var $ESignModel ESignModel */
            $ESignModel = D('ESign');
            $account = $this->getUserSeal($userId);
            $accountId = $account['account_id'];

            //获取用户签章
            $signatureData = $ESignModel->getSignatureData($account['signature_path']);

            //在合同上盖上用户印章
            $dstPdfFile = $this->getTempContractFile();  //临时合同文件
            $userSignPDF = $this->userSignPDF($accountId, $signatureData, $dstPdfFile);
            $userSignServiceId = $userSignPDF['signServiceId'];    //临时合同电子档ID

            //在合同上盖上平台印章
            $selfSignPDF = $this->selfSignPDF($dstPdfFile, $dstPdfFile);
            $contractSignServiceId = $selfSignPDF['signServiceId']; //合同电子档ID
            $contractData = base64_decode($selfSignPDF['stream']);
            $contractNumber = $this->getContractNumber();
            $contractId = $ESignModel->saveAccountContract($userId, $contractNumber, $contractSignServiceId, $contractData);

            //删除临时文件
            @unlink($dstPdfFile);
            return $ESignModel->getContractById($contractId);
        } catch (Exception $e) {
            $error = json_encode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
            logger('【ESign Error】 ' . $error);
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }


    /**
     * 注册e签宝账户唯一标识
     * @param string $mobile 用户手机号码
     * @param string $name 用户姓名
     * @param string $idNo //身份证号
     * @param int $personarea //用户归属地
     * @param string $email 邮箱
     * @param string $organ 所属公司
     * @param string $title 职位
     * @param string $address 常用地址
     * @return array|mixed|\tech\core\MyErrorException
     * @throws Exception
     */
    public function addPersonAccount($mobile, $name, $idNo, $personarea = 0, $email = '', $organ = '', $title = '', $address = '')
    {
        $ret = $this->getInstance()->addPersonAccount($mobile, $name, $idNo, $personarea, $email, $organ, $title, $address);
        if ($ret['errCode']) {
            throw new Exception($ret['msg'], $ret['errCode']);
        }
        return $ret;
    }

    /**
     * 为用户创建电子印章
     * @param $accountId
     * @return array|mixed|\tech\core\MyErrorException
     * @throws Exception
     */
    protected function addPersonTemplateSeal($accountId)
    {
        $ret = $this->getInstance()->addTemplateSeal(
            $accountId,
            $templateType = $this->templateType,
            $color = $this->color
        );
        if ($ret['errCode']) {
            throw new Exception($ret['msg'], $ret['errCode']);
        }
        return $ret;
    }

    /**
     * 平台用户摘要签署
     * @param string $accountId
     * @param string $sealData
     * @param string $dstPdfFile 签章后生成的合同临时文件 第2次盖平台章的时候需要
     * @return array|mixed
     * @throws Exception
     */
    protected function userSignPDF($accountId, $sealData, $dstPdfFile, $userSignPos = [])
    {
        $signFile = array(
            'srcPdfFile'    => $dstPdfFile,   //合同源文件
            'dstPdfFile'    => $dstPdfFile,
            'fileName'      => '',
            'ownerPassword' => '',
        );
        $ret = $this->getInstance()->userSignPDF($accountId, $signFile, $userSignPos, $this->signType, $sealData, $stream = true);

        if ($ret['errCode']) {
            throw new Exception($ret['msg'], $ret['errCode']);
        }
        return $ret;
    }

    /**
     * 平台自身摘要签署
     * @param string $srcPdfFile 临时合同文件
     * @param string $dstPdfFile 最终生成的合同文件路径 此时该合同上有 用户签章 和平台签章
     * @return array|mixed
     * @throws Exception
     */
    protected function selfSignPDF($srcPdfFile, $dstPdfFile, $sealId = 0, $selfSignPos = [])
    {
        $signFile = array(
            'srcPdfFile'    => $srcPdfFile,
            'dstPdfFile'    => $dstPdfFile,
            'fileName'      => '',
            'ownerPassword' => '',
        );
        $ret = $this->getInstance()->selfSignPDF($signFile, $selfSignPos, $sealId, $this->signType, $stream = true);
        if ($ret['errCode']) {
            throw new Exception($ret['msg'], $ret['errCode']);
        }
        return $ret;
    }

    /**
     * 设置临时合同文件路径
     * @param string $tempContractFilePath
     */
    public function setTempContractFilePath($tempContractFilePath = '')
    {
        if (empty($tempContractFilePath)) {
            $tempContractFilePath = realpath(RUNTIME_PATH) . DIRECTORY_SEPARATOR . 'Temp';
        }
        $this->tempContractFilePath = $tempContractFilePath;
    }

    /**
     * 获取临时合同的文件
     * @return string
     */
    private function getTempContractFile()
    {
        if (empty($this->tempContractFilePath)) {
            $this->setTempContractFilePath();
        }
        $filename = microtime() . mt_rand(1, 99999);
        return $this->tempContractFilePath . DIRECTORY_SEPARATOR . $filename . '.pdf';
    }
}