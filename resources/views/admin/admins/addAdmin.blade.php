@extends('admin.layouts.index')


@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Nhân viên, quản lý
                    <small>Thêm</small>
                </h1>
                <form action="{{ route('admin.add') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" placeholder="Nhập email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu:</label>
                        <input type="password" class="form-control" placeholder="Nhập password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Vị trí:</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="0">Admin</option>
                            <option value="1">Nhân viên</option>
                            <option value="2">Quản lý</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                  </form>
            </div>
        </div>
    </div>
</div>
@endsection
