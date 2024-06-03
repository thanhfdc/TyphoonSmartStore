<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    const NOT_SUCCESS = 0;
    const SUCCESS = 2;
    const CANCEL = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id','promotion_id','product_id','order_code','status','qty','price'];
    protected $table = "orders";

    /**
     * @param $query
     * @param $year
     * @return mixed
     */

    public function scopeGetCountOrderByStatus($query, $year)
    {
        return $query->select(DB::raw(
            'CASE WHEN `status` = 0 THEN COUNT(id)
            WHEN `status` = 2 THEN  COUNT(id)
            WHEN `status` = 3 THEN  COUNT(id)
            END  as count_order ,status'))
            ->whereYear('created_at', $year)
            ->groupBy('status');
    }

    /**
     * @param $query
     * @param $year
     * @return mixed
     */

    public function scopeGetDataOrderByMonthAndYear($query , $year)
    {
        return $this->select(DB::raw('COUNT(id)  as count_month_order , MONTH(created_at) as month_order, sum(price) as doanh_thu_thang'))
            ->whereYear('created_at', $year)
            ->where('status' , Order::SUCCESS)
            ->groupBy(DB::raw(' MONTH(created_at)'));
    }
}
