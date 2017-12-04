<?php

namespace App\Api\Controllers;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends BaseController
{
    //

    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function index(Request $request)
    {
        $pageSize = $request->input('page_size', PAGE_SIZE);

        $uid = $request->input('uid');
        $username = $request->input('username');
        $idCard = $request->input('idCard');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        /**
         * 查询字段
         */
        $columns = [
            '*',
        ];

        /* @var $paginate LengthAwarePaginator */
        $paginate = $this->order->when($uid, function (Builder $query) use ($uid) {
            $query->where('id', $uid);
        })->when($username, function (Builder $query) use ($username) {
            $query->where('username', 'like', "%{$username}%");
        })->when($idCard, function (Builder $query) use ($idCard) {
            $query->where('id_card', 'like', "%{$idCard}%");
        })->when($dateStart, function (Builder $query) use ($dateStart) {
            $query->where('created_time', '>=', $dateStart);
        })->when($dateEnd, function (Builder $query) use ($dateEnd) {
            $query->where('created_time', '<=', $dateEnd);
        })
            ->orderBy('id', 'desc')
            ->paginate($pageSize, $columns);
        $accessRecords = $paginate->getCollection();

        $accessRecords->each(function (Order $user) {
            //格式化数据

        });

        return $this->responseData($paginate);
    }
}
