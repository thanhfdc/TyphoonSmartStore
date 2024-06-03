@extends('admin.layouts.index')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard
                        <small>Thống kê</small>
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
                <div class="col-md-12">
                    <div class="col-md-6"></div>
                    <div class="col-md-6" style="justify-content: flex-end ; display: flex">
                        <div class="box">
                            <label for="cars">Chọn năm : </label>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <select name="year_box" id="year_box">
                                @foreach($yearSelect as $year)
                                    <option value={{ $year }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box-init" style="display: flex;justify-content: space-evenly; margin-bottom: 8rem">
                        <div class="col-md-3">
                            <div class="box-1" style="background: #f76455">
                                <span class="js-span-1">Chưa xác nhận : {{ data_get($statisticOrderStatus , '0.count_order' , 0) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box-1" style="background: #5b8f46">
                                <span class="js-span-2">Xác nhận : {{ data_get($statisticOrderStatus , '1.count_order' ,0) }}</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="box-1" style="background: black">
                                <span class="js-span-3">Hủy : {{ data_get($statisticOrderStatus , '2.count_order' , 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="col-md-6"></div>
                        <div class="col-md-6" style="justify-content: flex-end ; display: flex">
                            <div class="box">
                                <label for="cars">Chọn năm : </label>

                                <select name="year" id="select_year_chart">
                                    @foreach($yearSelect as $year)
                                        <option value={{ $year }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12" style="padding-left: 0px ; padding-right: 0px">
                        <div id="chartContainer" style="height: 370px; width: 105%;"></div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <style>
        .box-1{
            height: 85px;
            color: white;
            border-radius: 5px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }
        #page-wrapper {
            margin: 0 0 0 222px;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        //     chart clear code

        function chartClear(parseData , year = "<?= $year + env('COUNT_YEAR') ?>")
        {
            var options = {
                title: {
                    text: "Thống kê theo năm "+ year
                },
                axisY: {
                    includeZero: true
                },
                data: [{
                    type: "column",
                    yValueFormatString: "#,###"+' đơn hàng',
                    indexLabel: "{y} : {total_price} đ",
                    color: "#546BC1",
                    dataPoints:parseData
                }]
            };
            $("#chartContainer").CanvasJSChart(options);

            function updateChart() {
                $("#chartContainer").CanvasJSChart().render();
            };
            updateChart();

            setInterval(function () { updateChart() }, 500);

        }

        window.onload = function () {
            let parseData =  <?= json_encode($defaultMonthInYear) ?>;
            chartClear(parseData);

        }

        $(document).ready(function (){
            $('#year_box').change(function (){
               let value = $(this).val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                    url: "<?= route('status.order') ?>",
                    type: "post",
                    data: {'year':value} ,
                    success: function (response, textStatus, jqXHR) {
                        response.data.map(function (val){
                            if(val.status == 0)
                            {
                                $('.js-span-1').text('Chưa xác nhận : '+val.count_order)
                            }

                            if(val.status == 2)
                            {
                                $('.js-span-2').text('Xác nhận : '+val.count_order)
                            }

                            if(val.status == 3)
                            {
                                $('.js-span-3').text('Hủy : '+val.count_order)
                            }
                        })

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }


                });
            })


        //     chart

            $('#select_year_chart').change(function (){
                let val = $(this).val()
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
                    url: "<?= route('status.order') ?>",
                    type: "post",
                    data: {'year':val ,'type':'chart'} ,
                    success: function (response, textStatus, jqXHR) {
                        chartClear(response.data , val);

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }


                });
            })
        })
    </script>
@endsection
