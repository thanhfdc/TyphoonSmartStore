@extends('admin.layouts.index')


@section('content')
<div id="page-wrapper">
    @if($errors->has('images.0'))
        <div class="alert alert-danger alert-dismissible">
            <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{$errors->first('images.0')}}
        </div>
    @endif
    @if(Session::has('invalid'))
        <div class="alert alert-danger alert-dismissible">
            <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{Session::get('invalid')}}
        </div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Sản phẩm
                    <small>Thêm</small>
                </h1>
                <form action="{{ route('product.add') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="form-group">
                        <label for="title">Tên sản phẩm:</label>
                        <input type="text" class="form-control" placeholder="Nhập tên sản phẩm" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="sku">Mã sản phẩm:</label>
                        <input type="text" class="form-control" placeholder="Nhập tên sản phẩm" id="sku" name="sku" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá tiền:</label>
                        <input type="number" min="0" step="1" class="form-control" placeholder="Nhập giá tiền" id="price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Số lượng:</label>
                        <input type="number" min="0" step="1" class="form-control" placeholder="Nhập số lượng" id="quantity" name="quantity" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Mô tả sản phẩm:</label>
                        <textarea class="form-control" id="content" name="content"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Danh mục sản phẩm:</label>
                        <select class="form-control" name="category_id" id="category_id">
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}">{{ $category['title'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="producer_id">Nhà cung cấp:</label>
                        <select class="form-control" name="producer_id" id="producer_id">
                            @foreach ($producers as $producer)
                                <option value="{{ $producer['id'] }}">{{ $producer['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Thương hiệu sản phẩm:</label>
                        <select class="form-control" name="brand_id" id="brand_id">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Chọn hình ảnh</label>
                        <input id="image" multiple type="file" name="images[]" required>
                    </div>
                    <div class="box-img" style="display:flex">
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                  </form>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $(document).ready(function() {
        $("#image").change(function(e) {
            $(".box-img").html('')
            let html = ''
            let file = e.target.files;
            const lengthFile = file.length
            for (let index = 0; index < lengthFile; index++) {
                imgURL = URL.createObjectURL(file[index]);
                html+= `<div class='child-img'>
                            <div class="item-img" style="width: 200px;">
                            <img style ="width: 100%;height: 100%;object-fit: cover;" style src='${imgURL}'>
                            </div>
                        </div>`

            }
           $(".box-img").append(`${html}`)

            // e.target.files.map(function(x) {
            //     console.log(x);
            // })
        })
    })
</script>
