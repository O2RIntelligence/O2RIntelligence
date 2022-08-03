@section('css')
    {{-- page css link here  --}}
     <!-- DataTables -->
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css">  --}}

    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/dataTables.bootstrap.min.css">
    <style type="text/css">
          /* for sm */

    .custom-switch.custom-switch-sm .custom-control-label {
        padding-left: 1rem;
        padding-bottom: 1rem;
    }

    .custom-switch.custom-switch-sm .custom-control-label::before {
        height: 1rem;
        width: calc(1rem + 0.75rem);
        border-radius: 2rem;
    }

    .custom-switch.custom-switch-sm .custom-control-label::after {
        width: calc(1rem - 4px);
        height: calc(1rem - 4px);
        border-radius: calc(1rem - (1rem / 2));
    }

    .custom-switch.custom-switch-sm .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(calc(1rem - 0.25rem));
    }

    /* for md */

    .custom-switch.custom-switch-md .custom-control-label {
        padding-left: 2rem;
        padding-bottom: 1.5rem;
    }

    .custom-switch.custom-switch-md .custom-control-label::before {
        height: 1.5rem;
        width: calc(2rem + 0.75rem);
        border-radius: 3rem;
    }

    .custom-switch.custom-switch-md .custom-control-label::after {
        width: calc(1.5rem - 4px);
        height: calc(1.5rem - 4px);
        border-radius: calc(2rem - (1.5rem / 2));
    }

    .custom-switch.custom-switch-md .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(calc(1.5rem - 0.25rem));
    }

    /* for lg */

    .custom-switch.custom-switch-lg .custom-control-label {
        padding-left: 3rem;
        padding-bottom: 2rem;
    }

    .custom-switch.custom-switch-lg .custom-control-label::before {
        height: 2rem;
        width: calc(3rem + 0.75rem);
        border-radius: 4rem;
    }

    .custom-switch.custom-switch-lg .custom-control-label::after {
        width: calc(2rem - 4px);
        height: calc(2rem - 4px);
        border-radius: calc(3rem - (2rem / 2));
    }

    .custom-switch.custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(calc(2rem - 0.25rem));
    }

    /* for xl */

    .custom-switch.custom-switch-xl .custom-control-label {
        padding-left: 4rem;
        padding-bottom: 2.5rem;
    }

    .custom-switch.custom-switch-xl .custom-control-label::before {
        height: 2.5rem;
        width: calc(4rem + 0.75rem);
        border-radius: 5rem;
    }

    .custom-switch.custom-switch-xl .custom-control-label::after {
        width: calc(2.5rem - 4px);
        height: calc(2.5rem - 4px);
        border-radius: calc(4rem - (2.5rem / 2));
    }

    .custom-switch.custom-switch-xl .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(calc(2.5rem - 0.25rem));
    }
</style>
    @endsection
    @extends('googleAds.partials.layout')
    @section('content')
        {{--    <div class="content-wrapper" id="pjax-container" style="min-height: 860px;">--}}

    <div id="app">
        <section class="content-header">
            <h1>
                General variables
                <small> </small>
            </h1> 
            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{url('google-ads/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                    General variables
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
                                <a href="javascript:void(0)" class="btn btn-sm btn-twitter" title="Export"><i class="fa fa-download"></i><span class="hidden-xs"> Export</span></a>
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

                         
                        <table class="table table-hover grid-table" id="generalVariableTable">
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
                                <th class="column-username">Official Dollar</th>
                                <th class="column-name">Blue Dollar</th>
                                <th class="column-roles">PLUSM Discount (%)</th>
                                <th class="column-__actions__">Action</th>
                              </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> 
        </section>
    </div>
    @include('googleAds.general-variable.form-modal')
@endsection
@section('js') 
    {{-- <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script> --}}

    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script>
    <script>
      var current_page = "General variables";
      const __csrf_token = "{{@csrf_token()}}";
    </script> 

    <script src="{{asset('js/google_ads/google_ads_manager.js')}}"></script>
    <script src="{{asset('js/google_ads/general_variables.js')}}"></script>
@endsection
