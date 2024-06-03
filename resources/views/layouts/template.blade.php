<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<!-- Css -->
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" media="all" />
	<!-- Fontawesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
		integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<!-- jQuery -->
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="shortcut icon" type="image/png" href="{{asset('favicon.ico')}}" />
	<title>@yield('title')</title>
</head>

<body>
	<div class="wrapper">
		<!-- Header -->
		<!-- A grey horizontal navbar that becomes vertical on small screens -->
        <nav class="navbar navbar-expand-lg text-white header">
            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-list text-white"></i></button>
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item"><a href="#" class="nav-link"><i class="fas fa-phone-alt"></i> Hotline:0898 10 3236</a></li>
						@if (!empty(Session::get('customer')->id))
							<li class="nav-item"><a href="{{  route('account') }}"  class="nav-link"><i class="far fa-edit"></i>{{ Session::get('customer')->email }}</a></li>
						@else
							<li class="nav-item"><a href="{{  route('login') }}"  class="nav-link"><i class="fas fa-sign-in-alt"></i> Đăng nhập</a></li>
							<li class="nav-item"><a href="{{  route('register') }}"  class="nav-link"><i class="fas fa-key"></i> Đăng ký</a></li>
						@endif
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4 mb-4 logo">
            <a href="{{ route('index') }}">
                <h1 style="font-family: 'Exo 2', sans-serif; color: gray">ACCESSORIES
                <span style="color: #f46164">STORE</span></h1>
            </a>
            <form method="POST" action="{{ route('search.product') }}" class="form-search" enctype="multipart/form-data">
				@csrf
                <div class="form-group d-flex">
                    <input type="text" placeholder="Tìm kiếm..." class="search-text-box" name="q" />
                    <button type="submit" class="button-search"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <div class="cart">
                <a href="{{ route('cart') }}" class="text-dark cart-child">
                    <img src="{{asset('assets/img/cart/cart.png')}}" alt="cart" />
                    <span id="cart-total" class="cart-total ml-2 mr-2 mt-2">
						{{ Session::has('cart') ? Session::get('cart')->totalQty : '' }}
					</span>
                    <i class="fa fa-arrow-right mt-2"></i>
                </a>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg text-white bg-dark options">
            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#product">
                <i class="fas fa-list text-white"></i>
            </button>
            <div class="container">
                <div class="collapse navbar-collapse" id="product">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a href="{{  route('index') }}" class="nav-link">TRANG CHỦ</a></li>
                        <li class="nav-item"><a href="{{  route('introduce') }}" class="nav-link">GIỚI THIỆU</a></li>
                        <li class="nav-item"><a href="{{  route('article') }}" class="nav-link">BÀI VIẾT</a></li>
                        <li class="nav-item"><a href="{{  route('contact') }}" class="nav-link">LIÊN HỆ</a></li>
						<div class="dropdown">
							<a type="button" class="nav-link dropdown-toggle mt-1" style="font-size:14px;" data-toggle="dropdown">DANH MỤC</a>
							<ul class="dropdown-menu">
								@foreach ($categories as $category)
									<li><a class="pl-3" href="{{ route('product.category',['id' => $category->id]) }}">{{ $category->title }}</a></li>
								@endforeach
							</ul>
						</div>
                    </ul>
                </div>
            </div>
        </nav>
		<div class="container">
			@yield('content')
			<!-- Footer -->
			<div id="brands" class="row">
				<div class="col-lg-2">
					<img src="{{asset('assets/img/brands/brand1.png')}}" width="150px" height="100px" alt="" />
				</div>
				<div class="col-lg-2">
					<img src="{{asset('assets/img/brands/brand2.png')}}" width="150px" height="100px" alt="" />
				</div>
				<div class="col-lg-2">
					<img src="{{asset('assets/img/brands/brand3.png')}}" width="150px" height="100px" alt="" />
				</div>
				<div class="col-lg-2">
					<img src="{{asset('assets/img/brands/brand4.png')}}" width="150px" height="100px" alt="" />
				</div>
				<div class="col-lg-2">
					<img src="{{asset('assets/img/brands/brand5.png')}}" width="150px" height="100px" alt="" />
				</div>
				<div class="col-lg-2">
					<img src="{{asset('assets/img/brands/brand6.png')}}" width="150px" height="100px" alt="" />
				</div>
			</div>
			<div class="scrollback" id="scrollback">
				<i class="fas fa-arrow-circle-up float-right"></i>
			</div>
		</div>
		<div class="container-fluid" style="background-color:#F46164;">
			<div class="row p-4">
				<div class="col-lg-6 text-white">
					<h6>THÔNG TIN ĐẠI LÝ</h6>
					<div class="items">
						<p>ACCESSORIES STORE</p>
						<p>Chuyên cung cấp loại phụ kiện điện thoại</p>
						<p>Địa chỉ: 92 A Lê Thanh Nghị, Bách Khoa, Hai Bà Trưng, Hà Nội</p>
						<p>Số điện thoại: 0898 103 236</p>
						<p>Email: azula@gmail.com</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="{{asset('assets/js/script.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/js/reply.js')}}"></script>
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	<script>
		Stripe.setPublishableKey('pk_test_51HgKtQA1q67YUalkFwQkc2jdscUSgR0YNyEGU7x6IqODF0LMDWkhI7RRfon1waK1voxJNtSIb6jg3aqwmgAB9lmZ00ZKWKhrHa');

		var $form = $('#checkout-form');


		$form.submit(function(event) {
		$('#charge-error').addClass('hidden');
		$form.find('button').prop('disabled', true);
		Stripe.card.createToken({
			number: $('#card-number').val(),
			cvc: $('#card-cvc').val(),
			exp_month: $('#card-expiry-month').val(),
			exp_year: $('#card-expiry-year').val(),
			name: $('#card-name').val()
		}, stripeResponseHandler);
		return false;
		});

	function stripeResponseHandler(status, response) {
		if (response.error) {
			$('#charge-error').removeClass('hidden');
			$('#charge-error').text(response.error.message);
			$form.find('button').prop('disabled', false);
		} else {
			var token = response.id;
			$form.append($('<input type="hidden" name="stripeToken" />').val(token));

			// Submit the form:
			$form.get(0).submit();
		}
	}
	</script>
</body>

</html>
