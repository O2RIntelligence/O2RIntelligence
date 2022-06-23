@section('css')
    {{-- page css link here  --}}
@endsection
@extends('googleAds.partials.layout')
@section('content')
    {{--    <div class="content-wrapper" id="pjax-container" style="min-height: 860px;">--}}
    <style type="text/css"></style>

    <div id="app">
        <section class="content-header">
            <h1>
                Dashboard
                <small> </small>
            </h1>

            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="http://localhost/o2r/public/admin"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                    Dashboard
                </li>
            </ol>

            <!-- breadcrumb end -->

        </section>

        <section class="content">


            <div class="dashboard-report">
                <div class="loader" style="display: none;">
                    <div class="lds-hourglass"></div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="pull-left">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-info active">
                                        <input name="page_type" type="radio" value="dates" checked=""> General
                                    </label>
                                    <label class="btn btn-info">
                                        <input name="page_type" type="radio" value="seats"> Seats
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-3">
                        <div class="box grid-box with-border">
                            <div class="box-header with-border text-center">
                                <b>Daily Net Income Projection</b>
                            </div>
                            <div class="box-body text-center">
                                <h2 id="DNI_Projection">38,426.702$</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box grid-box with-border">
                            <div class="box-header with-border text-center">
                                <b>Monthly Net Income Projection</b>
                            </div>
                            <div class="box-body text-center">
                                <h2 id="MNI_Projection">1,044,467.227$</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box grid-box">
                            <div class="box-header with-border text-center">
                                <b>{{ __('Daily NET Income') }}</b>
                            </div>
                            <div class="box-body">
                                <div class="isResizable">
                                    <div class="chart-container">
                                        <canvas id="DNetIncome_container" style="width:100%;height:auto;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="box grid-box">
                            <div class="box-header with-border text-center">
                                <b>{{ __('Hourly NET Income') }}</b>
                            </div>
                            <div class="box-body">
                                <div class="isResizable">
                                    <div class="chart-container">
                                        <canvas id="HNetIncome_container" style="width:100%;height:auto;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="clear"></div>

            <div class="clear"></div>

            <!-- Modal -->
            <div class="modal fade" id="datatables-controls" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Manage Table</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="btn-group" data-toggle="buttons">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-container">
            </div>
            <style>
                .swal2-popup {
                    font-size: 14px !important;
                }

                .loading {
                    float: left;
                    top: 50%;
                    left: 50%;
                    margin-top: 112px;
                    margin-left: -61px;
                    margin-bottom: -248px;
                    border-left: 1px solid #fff;
                    border-bottom: 1px solid #fff;
                    box-sizing: border-box;
                }

                @keyframes loading {
                    0% {
                        background-color: #cd0a00;
                    }
                    30% {
                        background-color: #fa8a00;
                    }
                    50% {
                        height: 100px;
                        margin-top: 0px;
                    }
                    80% {
                        background-color: #91d700;
                    }
                    100% {
                        background-color: #cd0a00;
                    }
                }

                /*@-moz-keyframes loading {
                  50% { height: 100px; margin-top: 0px; }
                }
                @-o-keyframes loading {
                  50% { height: 100px; margin-top: 0px; }
                }
                @keyframes  loading {
                  50% { height: 100px; margin-top: 0px; }
                }*/
                .loading .loading-1 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 0.25s;
                    animation-delay: 0.25s;
                }

                .loading .loading-2 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 0.5s;
                    animation-delay: 0.5s;
                }

                .loading .loading-3 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 0.75s;
                    animation-delay: 0.75s;
                }

                .loading .loading-4 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 1s;
                    animation-delay: 1s;
                }

                .loading .loading-5 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 1.25s;
                    animation-delay: 1.25s;
                }

                .loading .loading-6 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 1.5s;
                    animation-delay: 1.5s;
                }

                .loading .loading-7 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 1.75s;
                    animation-delay: 1.75s;
                }

                .loading .loading-8 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 2s;
                    animation-delay: 2s;
                }

                .loading .loading-9 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 2.25s;
                    animation-delay: 2.25s;
                }

                .loading .loading-10 {
                    height: 10px;
                    width: 30px;
                    background-color: #fff;
                    display: inline-block;
                    margin-top: 90px;
                    -webkit-animation: loading 2.5s infinite;
                    -moz-animation: loading 2.5s infinite;
                    -o-animation: loading 2.5s infinite;
                    animation: loading 2.5s infinite;
                    border-top-left-radius: 2px;
                    border-top-right-radius: 2px;
                    -webkit-animation-delay: 2.5s;
                    animation-delay: 2.5s;
                }
            </style>
            <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>

            <script>
                var current_page = "GoogleAds dashboard";
            </script>
        </section>
    </div>
{{--    <script data-exec-on-popstate="">$(function () {--}}
{{--            ;(function () {--}}
{{--                $('.container-refresh').off('click').on('click', function () {--}}
{{--                    $.admin.reload();--}}
{{--                    $.admin.toastr.success('Refresh succeeded !', '', {positionClass: "toast-top-center"});--}}
{{--                });--}}
{{--            })();--}}
{{--        });</script>--}}


    {{--    </div>--}}
@endsection
@section('js')
    {{-- page js link here  --}}
@endsection
