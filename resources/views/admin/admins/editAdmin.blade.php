@extends('admin.layouts.index')


@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                @php
                    $admin = \App\Admin::find($admin["id"]);
                @endphp
                <h1 class="page-header">{{ $admin->role == 0 ? 'Quản trị viên' : 'Nhân viên, quản lý' }}
                    <small>Sửa</small>
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
                <form action="{{ route('admin.edit',['id' => $admin['id']]) }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" placeholder="Nhập email" id="email" name="email" value='{{ $admin['email'] }}' required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" class="form-control" placeholder="Nhập mật khâu" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="role">Vị trí:</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="0" {{ $admin["role"] == 0 ? 'selected' : '' }}>Admin</option>
                            <option value="1" {{ $admin["role"] == 1 ? 'selected' : '' }}>Nhân viên</option>
                            <option value="2" {{ $admin["role"] == 2 ? 'selected' : '' }}>Quản lý</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Sửa</button>
                  </form>
            </div>
        </div>
    </div>
</div>
@endsection
