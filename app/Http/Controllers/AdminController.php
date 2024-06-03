<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showFormLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('email', '=', $request->email)->where('password','=',md5($request->password))->first();
        if ($admin === null) {
            return redirect()->back()->with('invalid','Email/Mật khẩu không đúng');
        } else{
            if(Session::has('admin')){
                Session::forget('admin');
                Session::put('admin',$admin);
            }else{
                Session::put('admin',$admin);
            }
            if ($admin['role'] == 0 || $admin['role'] == 2) {
                return redirect()->route('dashboard')->with('success','Đăng nhập thành công');
            } else {
                return redirect()->route('customer.list')->with('success','Đăng nhập thành công');
            }
        }
    }

    public function logout()
    {
        Session::forget('admin');
        return redirect()->route('admin.login')->with('success','Đăng xuất thành công.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_user_login = Auth::id();
         $admins = Admin::get();
        return view('admin.admins.listAdmin',['admins'=>$admins , 'id_user_login' => $id_user_login]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admins.addAdmin');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Form validation
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);
        //  Store data in database
        $producer = new Admin([
            'email' => $request->input('email'),
            'password' => md5($request->input('password')),
            'role' => $request->input('role')
        ]);
        $producer->save();
        return redirect()->route('admin.list')->with("success","Lưu thành công");
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
        $admin = Admin::find($id);
        return view('admin.admins.editAdmin',['admin' => $admin]);
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
        $admin = Admin::find($id);
        $admin->email = $request->input('email');
        $admin->password = md5($request->input('password'));
        $admin->role = $request->input('role');
        $admin->save();
        if ($request->input('role') == 0) {
            return redirect()->back()->with("success","Sửa thành công");
        }
        return redirect()->route('admin.list')->with("success","Sửa thành công");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = Admin::where('id' ,'<>' ,Session::get('admin')->id)->first();
        $a = $admin->delete();
        if($a)
        {
            return redirect()->route('admin.list')->with("success","Xóa thành công");
        }else
        {
            return redirect()->route('admin.list')->with("success","Không được xóa");
        }
    }
}
