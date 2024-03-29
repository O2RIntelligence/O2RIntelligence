@section('css')
    {{-- page css link here  --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <style>
        .hide-filter-item {
            display: none !important;
        }

        .custom-filter-dropdown {
            padding-left: 10px;
            padding-right: 10px;
        }

        .custom-filter-dropdown .dropdown-item:hover {
            cursor: pointer;
        }

        .dataTables_length {
            float: left;
            padding-left: 10px;
        }

        .dataTables_paginate,
        .dataTables_filter {
            padding-right: 10px
        }

        .dataTables_info {
            padding-left: 10px
        }

        .dt-buttons {
            float: left;
            margin-left: 10px;
            margin-top: 10px
        }


        .table > thead:first-child > tr:first-child > th {
            border-top: 1px solid #eee;
        }

        .table.dataTable thead th,
        .table.dataTable thead td {
            padding: 10px;
            border-bottom: 1px solid #fff;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #eee;
        }

        .table th,
        .table td {
            border-right: 1px solid #eee;
        }

        .table .filters th input {
            border: 1px solid #c9c9c9
        }

        .table .filters th .dropdown-toggle {
            border-color: #c9c9c9;
            border-left: 0 !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25em 0.8em;
        }

        .dataTables_wrapper .dataTables_paginate,
        .dataTables_info {
            margin-bottom: 10px;
            margin-top: 5px;
        }

        .table.dataTable {
            min-height: .01%;
            overflow-x: auto;
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
                Financial Report
                <small> </small>
            </h1>
            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{url('google-ads/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                    Financial report
                </li>
            </ol>
            <!-- breadcrumb end -->
        </section>

        @include('googleAds.filter')

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box grid-box">
                        <div class="box-body table-responsive">
                            <table class="table table-hover grid-table" id="financialTable">
                                <thead>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('js')

    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <!--    <script src="//cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script>-->


    <script>
      var current_page = "GoogleAds dashboard";
      const __csrf_token = "{{@csrf_token()}}";
    </script>

    <script src="{{asset('js/google_ads/google_ads_manager.js')}}"></script>
    <script src="{{asset('js/google_ads/financial_report.js')}}"></script>
@endsection
