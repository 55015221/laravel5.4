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

    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', PAGE_SIZE);

        $code = trim($request->input('code'));

        /**
         * 查询字段
         */
        $columns = [
            'access_records.*',
            'users.username'
        ];

//        DB::connection()->enableQueryLog();
        /* @var $paginate LengthAwarePaginator */
        $paginate = AccessRecord::leftJoin('users', 'users.id', '=', 'access_records.uid')
            ->when($code, function (Builder $query) use ($code) {
                $query->where('access_records.code', $code);
            })
            ->orderBy('access_records.id', 'desc')
            ->paginate($pageSize, $columns);

//        logger('22',DB::getQueryLog());
        $accessRecords = $paginate->getCollection();

        $accessRecords->each(function (AccessRecord $accessRecord) {
            //格式化数据

        });

        return $this->responseData($paginate);
    }

}