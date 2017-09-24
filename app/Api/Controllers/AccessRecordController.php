<?php
/**
 * api访问记录
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;


use App\Models\AccessRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AccessRecordController extends BaseController
{

    /**
     * 获取访问记录列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', PAGE_SIZE);

        $code = trim($request->input('code'));
        $url = trim($request->input('url'));

        /**
         * 查询字段
         */
        $columns = [
            'access_records.*',
            'users.username'
        ];

        /* @var $paginate LengthAwarePaginator */
        $paginate = AccessRecord::leftJoin('users', 'users.id', '=', 'access_records.uid')
            ->when($code, function (Builder $query) use ($code) {
                $query->where('access_records.code', $code);
            })
            ->when($url, function (Builder $query) use ($url) {
                $query->where('access_records.url', 'like', "%{$url}%");
            })
            ->orderBy('access_records.id', 'desc')
            ->paginate($pageSize, $columns);

        $accessRecords = $paginate->getCollection();
        $accessRecords->each(function (AccessRecord &$accessRecord) {
            //格式化数据
        });

        $paginate->setCollection($accessRecords);

        return $this->responseData($paginate);
    }

    /**
     * 获取记录详情
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function detail(Request $request, $id)
    {
        /**
         * @var $accessRecord AccessRecord
         */
        $accessRecord = AccessRecord::where('id', $id)->first();

        $accessRecord->response = json_decode($accessRecord->response,true);
        return $this->responseData($accessRecord);
    }

}