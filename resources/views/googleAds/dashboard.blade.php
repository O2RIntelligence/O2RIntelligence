@section('css')
    {{-- page css link here  --}}
@endsection
@extends('googleAds.partials.layout')
@section('content')
    <div id="app">
        <section class="content-header">
            <h1>
                Dashboard
                <small> </small>
            </h1>

            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{url('google-ads/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
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

                @include('googleAds.filter')
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body" style="padding-left: 0">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-info active">
                                            <input name="page_type" type="radio" value="general" checked=""> General
                                        </label>
                                        <label class="btn btn-info">
                                            <input name="page_type" type="radio" value="muster-account"> Muster Account
                                        </label>
                                        <label class="btn btn-info">
                                            <input name="page_type" type="radio" value="sub-account"> Sub Account
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-7 text-right">
                                    <div class="pull-right time-periods">
                                        <input type="hidden" name="date_period" value="today">
                                        <button data-period="today" class="btn btn-secondary change-period active" type="button">Today</button>
                                        <button data-period="yesterday" class="btn btn-secondary change-period" type="button">Yesterday</button>
                                        <button data-period="last7" class="btn btn-secondary change-period" type="button">Last 7 Days</button>
                                        <button data-period="last30" class="btn btn-secondary change-period" type="button">This Month</button>
                                        <button data-period="custom" class="btn btn-secondary change-period" id="daterange-btn" type="button">
                                            <i class="fa fa-calendar"></i> Custom Range
                                            <i class="fa fa-caret-down"></i>
                                        </button>  
                                    </div>
                                </div>
                            </div> 
                        </div>

                        
                        <div class="box grid-box with-border"> 
                            <div class="box-body">  
                                <div class="row"> 
                                    <div class="col-md-11 text-right">
                                        <select name="account-filter" id="account-filter" class="form-control" multiple>
                                            <option value="1">Account one</option>
                                            <option value="2">Account two</option>
                                            <option value="3">Account three</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 text-right">
                                        <button class="refresh-seats btn-black btn btn-block" style="max-height: 32px">Refresh</button>
                                    </div>
                                 </div> 
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="box grid-box with-border">
                            <div class="box-header with-border text-center">
                                <b>Daily Total Cost</b>
                            </div>
                            <div class="box-body text-center">
                                <h2 id="daily_total_cost"></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box grid-box with-border">
                            <div class="box-header with-border text-center">
                                <b>Daily Run Rate</b>
                            </div>
                            <div class="box-body text-center">
                                <h2 id="daily_run_rate"></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box grid-box with-border">
                            <div class="box-header with-border text-center">
                                <b>Monthly Total Cost</b>
                            </div>
                            <div class="box-body text-center">
                                <h2 id="monthly_total_cost"></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="box grid-box with-border">
                            <div class="box-header with-border text-center">
                                <b>Monthly Run Rate</b>
                            </div>
                            <div class="box-body text-center">
                                <h2 id="monthly_run_rate"></h2>
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
                                        <canvas id="line_chart_one" style="width:100%;height:auto;"></canvas>
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
                                        <canvas id="line_chart_two" style="width:100%;height:auto;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
@endsection
@section('js')
    {{-- page js link here  --}}
    <script src="{{asset('new-dashboard/chart.js')}}"></script>  
    @include('googleAds.common_script')
    <script>
      var current_page = "GoogleAds dashboard";
      const __csrf_token = "{{@csrf_token()}}";
    </script>
    <script src="/js/google_ads/google_ads_manager.js"></script>
    <script src="/js/google_ads/dashboard.js"></script>
@endsection