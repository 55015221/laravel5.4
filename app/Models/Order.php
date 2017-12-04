<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $connection = 'mysql_163';

    protected $table = 'lxj_r_lxd_rong_order_info';
    /**
     * 不可被批量赋值的属性。
     * @var array
     */
    protected $guarded = [];

    const ORDER_STATUS = [
        101 => '申请中',
        201 => '审核中',
        202 => '审核拒绝',
        203 => '审核通过，确认中',
        302 => '确认失败',
        303 => '确认成功，放款中',
        402 => '放款失败',
        403 => '放款成功，还款中',
        502 => '还款失败',
        503 => '还款成功',
    ];
}
