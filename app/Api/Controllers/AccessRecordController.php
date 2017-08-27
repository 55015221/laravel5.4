<?php
/**
 * api访问记录
 *
 * @author Bily
 * @date 2017-8-7 11:40:20
 */

namespace App\Api\Controllers;


use App\Models\AccessRecord;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AccessRecordController extends BaseController
{

    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', PAGE_SIZE);

        /**
         * 查询字段
         */
        $columns = [
            'access_records.*',
            'users.username'
        ];

        /* @var $paginate LengthAwarePaginator */
        $paginate = AccessRecord::leftJoin('users', 'users.id', '=', 'access_records.uid')
            ->orderBy('access_records.id', 'desc')
            ->paginate($pageSize, $columns);

        $accessRecords = $paginate->getCollection();

        $accessRecords->each(function (AccessRecord $accessRecord) {
            //格式化数据

        });

        return $this->responseData($paginate);
    }

}