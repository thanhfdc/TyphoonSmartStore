 <!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('dashboard') }}">Accessories Store</a>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        <!-- /.dropdown -->
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                @if (Session::get('admin')->role == 0)
                <li><a href="{{ route('admin.edit.form', ['id' => Session::get('admin')->id ]) }}"><i class="fa fa-user" aria-hidden="true"></i> Thông tin cá nhân</a></li>
                @endif
                <li><a href="{{ route('admin.logout') }}"><i class="fa fa-sign-out fa-fw"></i> Đăng xuất</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    @include('admin.layouts.menu')
    <!-- /.navbar-static-side -->
</nav>
