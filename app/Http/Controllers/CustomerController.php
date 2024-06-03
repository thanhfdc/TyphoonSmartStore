<?php

namespace App\Http\Controllers;

use App\Ward;
use Illuminate\Http\Request;
use App\Customer;
use App\Order;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::with(['city','district'])
            ->join('wards' ,'wards.xaid','=','customers.ward_id' )
            ->get();
        $customers = $customers->map(function ($item){
            $item['xa'] = ['xaid'=> $item['xaid'] ,'name' => $item['name']];
            return $item;
        });
        return view('admin.customers.listCustomer',['customers'=>$customers]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orders = Order::where('customer_id' , $id)->get();
        foreach($orders as $order)
        {
             Order::where('id' , $order->id)->delete();
        }


        $customer = Customer::find($id);
        $customer->delete();
        return redirect()->route('customer.list')->with("success","Xóa thành công");
    }

         /**
     * Disable account
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        Customer::where('id',$id)->update(['status' => 0]);
        return redirect()->back()->with('success','Khóa tài khoản thành công.');
    }

     /**
     * Enable account
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        Customer::where('id',$id)->update(['status' => 1]);
        return redirect()->back()->with('success','Mở tài khoản thành công.');
    }
}
