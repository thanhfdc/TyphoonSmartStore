@extends('admin.layouts.index')


@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Nhân viên, quản lý
                    <small>Danh sách</small>
                </h1>
                @if(Session::has('invalid'))
                    <div class="alert alert-danger alert-dismissible">
                         <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
                         {{Session::get('invalid')}}
                    </div>
               @endif
               @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible">
                         <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
                         {{Session::get('success')}}
                    </div>
               @endif
            </div>
            <!-- /.col-lg-12 -->
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr>
                        <th>Số thứ tự</th>
                        <th>Email</th>
                        <th>Vị trí</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody align="center">
                    @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin['id'] }}</td>
                            <td>{{ $admin['email'] }}</td>
                            <td>
                                @if($admin['role'] == 1)
                                Nhân viên
                                @elseif($admin['role'] == 0)
                                Admin
                                @else
                                Quản lí
                                @endif
                            </td>
                            <td>
                                @if($admin['id'] != Session::get('admin')->id)
                                <a href="{{ route('admin.delete',['id'=>$admin['id']]) }}" onclick="return confirm('Bạn có muốn xóa người dùng này ?')"><i class="fa fa-times" aria-hidden="true"></i> </a>
                                @endif
                                <a href="{{ route('admin.edit.form',['id'=>$admin['id']]) }}" style="margin:0 1rem;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
@endsection
