<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::select('select o.order_code,o.status,c.username,p.code,sum(o.price) total_money from orders o,customers c,promotions p 
        where o.customer_id = c.id and o.promotion_id = p.id group by o.order_code');
        return view('admin.orders.listOrder',['orders'=>$orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $products = [];
        $products = DB::select('select o.*,p.title,p.price from orders o,products p where o.product_id = p.id and o.order_code = ?',[$code]);
        return view('admin.orders.seeOrder',['products' => $products]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $code)
    {
        Order::where('order_code',$code)->update(['status' => $request->input('status')]);
        return redirect()->route('order.list')->with('success','Cập nhật trạng thái thành công.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showMyOrder($id)
    {
        $orders = DB::table('orders')
        ->join('promotions', 'promotions.id', '=', 'orders.promotion_id')
        ->where('orders.customer_id','=',$id)
        ->groupBy('orders.order_code')  
        ->paginate(3,['orders.order_code','orders.status','promotions.code'],'order');
        return view('my-order',['orders'=>$orders]);
    }

    /**
     * See detail my order
     * @param  int  $code
     * @return \Illuminate\Http\Response
     */
    public function seeMyOrder($code)
    {
        $products = [];
        $products = DB::select('select o.qty,p.title,p.price from orders o,products p,promotions s where o.product_id = p.id and o.promotion_id = s.id group by ?,p.id',[$code]);
        return view('my-order-detail',['products' => $products]); 
    }
}
