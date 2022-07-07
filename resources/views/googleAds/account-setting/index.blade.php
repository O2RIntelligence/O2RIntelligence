@section('css')
    {{-- page css link here  --}}
     <!-- DataTables -->
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css"> --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/dataTables.bootstrap.min.css"> 
    <style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 24px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
@endsection
@extends('googleAds.partials.layout')
@section('content')
    {{--    <div class="content-wrapper" id="pjax-container" style="min-height: 860px;">--}}
    <style type="text/css"></style>

    <div id="app">
        <section class="content-header">
            <h1>
                Account Setting
                <small> </small>
            </h1> 
            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{url('google-ads/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                    Account setting
                </li>
            </ol> 
            <!-- breadcrumb end --> 
        </section>

        <section class="content"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="box grid-box">
                        <div class="box-header with-border">
                            <div class="pull-right"> 
                                <!-- <div class="btn-group pull-right" style="margin-right: 10px">
                                    <a href="javascript:void(0)" target="_blank" class="btn btn-sm btn-twitter" title="Export"><i class="fa fa-download"></i><span class="hidden-xs"> Export</span></a>
                                    <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript:void(0)" target="_blank">All</a></li>
                                        <li><a href="javascript:void(0)" target="_blank">Current page</a></li>
                                        <li><a href="javascript:void(0)" target="_blank" class="export-selected">Selected rows</a></li>
                                    </ul>
                                </div>  -->
                                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                    <a href="javascript:void(0)" class="btn btn-sm btn-success" title="New" id="createFormDialog">
                                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;New</span>
                                    </a>
                                </div> 
                            </div> 
                        </div>

                        <table class="table table-hover grid-table" id="accountSettingsTable">
                            <thead>
                              <tr>
                                <!-- <th class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-select-all" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>&nbsp;
                                </th>
                                <th class="column-id">ID <a class="fa fa-fw fa-sort" href="http://127.0.0.1:8000/admin/auth/users?_sort%5Bcolumn%5D=id&amp;_sort%5Btype%5D=desc"></a>
                                </th> -->
                                <th class="column-username">Account Name</th>
                                <th class="column-name">Account Id</th>
                                <th class="column-roles">Developer Token</th>
                                <th class="column-created_at">Discount</th>
                                <th class="column-updated_at">Revenue Conversion Rate</th>
                                <th class="column-api_password">Active Status</th>
                                <th class="column-__actions__">Action</th>
                              </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> 
        </section>
    </div>
    @include('googleAds.account-setting.form-modal')
@endsection
@section('js') 
    {{-- <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script> --}}

    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script>
    <script>
      var current_page = "Account settings";
      const __csrf_token = "{{@csrf_token()}}";
    </script> 

    <script src="{{asset('js/google_ads/google_ads_manager.js')}}"></script>
    <script src="{{asset('js/google_ads/account_settings.js')}}"></script>
@endsection
