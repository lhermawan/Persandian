@extends('backend.layouts.app')

@section('content')

    <style>
        .axisLabel {
            position: absolute;
            text-align: center;
            font-size: 12px;
        }

        .xaxisLabel {
            bottom: 3px;
            left: 0;
            right: 0;
        }

        .yaxisLabel {
            top: 50%;
            left: 2px;
            transform: rotate(-90deg);
            -o-transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            -webkit-transform: rotate(-90deg);
            transform-origin: 0 0;
            -o-transform-origin: 0 0;
            -ms-transform-origin: 0 0;
            -moz-transform-origin: 0 0;
            -webkit-transform-origin: 0 0;
        }
    </style>

    <div class="p-w-md m-t-sm">

        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1"> Desa</a></li>
                <li class=""><a data-toggle="tab" href="#tab-2"> Data Persandian</a></li>
                <li class=""><a data-toggle="tab" href="#tab-3"> Data Serangan</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-turkish-lira  fa-5x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span> TOTAL TTE</span>
                                            <h2 class="font-bold">{{ $totaltte }} TTE</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-globe fa-5x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span> TOTAL WEBSITE </span>
                                            <h2 class="font-bold">{{ $totalwebsite }} Website</h2>
                                        </div>

                                    </div>
                                </div>
                            </div>



                            <div class="col-sm-4">
                                <div class="widget style1 lazur-bg">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <i class="fa fa-users fa-5x"></i>
                                        </div>
                                        <div class="col-xs-8 text-right">
                                            <span> TOTAL BIMTEK  </span>
                                            <h2 class="font-bold">{{ $totalsosialisasi }} Desa</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <h1 class="m-b-xs">
                                    {{ $hdiskominfo }}
                                </h1>
                                <small>
                                    Hosting Diskominfo
                                </small>

                            </div>

                            <div class="col-sm-4">
                                <h1 class="m-b-xs">
                                    {{ $hluar }}
                                </h1>
                                <small>
                                    Hosting Luar
                                </small>


                            </div>

                            <div class="col-sm-4">
                                <h1 class="m-b-xs">
                                    {{ $hweb }}
                                </h1>
                                <small>
                                    Belum Memiliki Website
                                </small>


                            </div>


                        </div>


                        <br>
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="ibox">

                                    <div class="ibox-title" style="border-width: 0 0 0;">
                                        <div class="ibox-tools">
                                            <form role="form" class="form-inline" action="{{ route('backend.home') }}"
                                                  method="GET">
                                                <div class="form-group">
                                                    <label for="cari" class="sr-only">Email address</label>
                                                    <input type="text" placeholder="nama poli atau dokter" id="cari"
                                                           name="cari" class="form-control" value="{{ $cari }}">
                                                </div>
                                                <div class="form-group">
                                                    <select class="form-control" id="durasi" name="durasi">
                                                        <option value="week" {{ $durasi == 'week' ? 'selected' : '' }}>
                                                            Minggu ini
                                                        </option>
                                                        <option
                                                            value="month" {{ $durasi == 'month' ? 'selected' : '' }}>
                                                            Bulan ini
                                                        </option>
                                                        <option value="year" {{ $durasi == 'year' ? 'selected' : '' }}>
                                                            Tahun ini
                                                        </option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-white" type="submit">Cari</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="ibox-content">

                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Poliklinik</th>
                                                <th>Jumlah booking</th>
                                                <th>Dokter</th>
                                                <th>Jam praktek</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($poliklinik as $data => $value)
                                                <tr>
                                                    <td>{{ $poliklinik->firstItem() + $data }}</td>
                                                    <td>{{ $value->poliklinik->namapoliklinik }}</td>
                                                    <td>{{ $value->jumlahBooking }}</td>
                                                    <td>{{ $value->dokter->namalengkap }}</td>
                                                    <td>{{ date('H:i',strtotime($value->jampraktek)) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        {{ $poliklinik->links() }}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-3" class="tab-pane">
                    <div class="panel-body">
                        <div class="row">
                            @foreach ($datakunjungan as $data => $value)
                            <div class="col-lg-4">
                                <div class="contact-box">
                                    <a href="#">
                                        <div class="col-sm-4">
                                            <div class="text-center">
                                                <img alt="image" class="img-circle m-t-xs img-responsive" src="{{ env('API_PATH').'/uploads/profiles/'.$value->fotopasien }}">
{{--                                                <div class="m-t-xs font-bold">Graphics designer</div>--}}
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <h3><strong>{{ preg_replace('/\s+?(\S+)?$/', '', substr($value->namapasien, 0, 20)) }}</strong></h3>
                                            <p><span class="label label-success"><i class="fa fa-info-circle"></i> {{ $value->jmlKunjungan }}</span></p>
                                            <address>
                                                <strong>{{ $value->telephone }}</strong><br>
                                                {{ $value->email }}<br>
                                            </address>
                                        </div>
                                        <div class="clearfix"></div>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection

@section('onpage-js')
    <!-- Flot -->
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.spline.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.symbol.js') }}"></script>
    <script src="{{ asset('plugin-inspinia/js/plugins/flot/jquery.flot.time.js') }}"></script>


    <!-- Sparkline -->
    <script src="{{ asset('plugin-inspinia/js/plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    @include('backend.layouts.message')

    <!-- ChartJS-->
    <script src=" {{ asset('plugin-inspinia/js/plugins/chartJs/Chart.min.js') }} "></script>

    <script>
        $(document).ready(function () {

            var sparklineCharts = function () {
                $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 52], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1ab394',
                    fillColor: "transparent"
                });

                $("#sparkline2").sparkline([32, 11, 25, 37, 41, 32, 34, 42], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1ab394',
                    fillColor: "transparent"
                });

                $("#sparkline3").sparkline([34, 22, 24, 41, 10, 18, 16, 8], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1C84C6',
                    fillColor: "transparent"
                });
            };

            var sparkResize;

            $(window).resize(function (e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();

            var weekly = {{ $week }};
            var weekbooking = {{ $weekbk }};
            var weekcancel = {{ $weekcl }};

            var lineData = {

                title: {
                    text: 'Date'
                },

                labels: weekly,//["January", "February", "March", "April", "May", "June", "July"],
                datasets: [
                    {
                        label: "Booking ",
                        backgroundColor: "rgba(26,179,148,0.5)",
                        borderColor: "rgba(26,179,148,0.7)",
                        pointBackgroundColor: "rgba(26,179,148,1)",
                        pointBorderColor: "#fff",
                        data: weekbooking // [28, 48, 40, 19, 100, 27, 90]
                    },
                    {
                        label: "Cancel",
                        backgroundColor: "rgba(255, 100, 90)",
                        borderColor: "rgba(255, 20, 60)",
                        pointBackgroundColor: "rgba(255, 20, 20)",
                        pointBorderColor: "#fff",
                        data: weekcancel //[65, 59, 80, 81, 56, 55, 40]
                    }
                ]
            };

            var lineOptions = {
                responsive: true
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            new Chart(ctx, {type: 'line', data: lineData, options: lineOptions});

        });
    </script>
@endsection
