@section('css')
    {{-- page css link here  --}}
     <!-- DataTables --> 
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <style>
        .filters .sorting::after {
            display: none !important;
        }

        .custom-filter-dropdown{
          padding-left: 10px;
          padding-right: 10px;
        }
        .custom-filter-dropdown .dropdown-item:hover{
           cursor: pointer;
        }

        .dataTables_length{
          float: left;
          padding-left: 10px; 
         }

         .dataTables_paginate,
         .dataTables_filter{
          padding-right: 10px
         }
         .dataTables_info{
          padding-left: 10px
         }
        .dt-buttons{float: left; margin-left: 10px; margin-top: 10px}

   
        .table>thead:first-child>tr:first-child>th {
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
      .table td{
        border-right: 1px solid #eee;
      }

      .table .filters th input{
        border: 1px solid #c9c9c9
      } 
      .table .filters th .dropdown-toggle{
        border-color: #c9c9c9;
        border-left: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
      }

      .dataTables_wrapper .dataTables_paginate .paginate_button { 
        padding: 0.25em 0.8em; 
      }
      .dataTables_wrapper .dataTables_paginate,
      .dataTables_info{ 
        margin-bottom: 10px;
        margin-top: 5px;
      }

      .table.dataTable{
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
                        <div class="box-header with-border"> 
                              <div class="btn-group pull-right" style="margin-right: 10px">
                                  <a href="http://127.0.0.1:8000/admin/auth/users?_export_=all" target="_blank" class="btn btn-sm btn-twitter" title="Export"><i class="fa fa-download"></i><span class="hidden-xs"> Export</span></a>
                                  <button type="button" class="btn btn-sm btn-twitter dropdown-toggle" data-toggle="dropdown">
                                      <span class="caret"></span>
                                      <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <ul class="dropdown-menu" role="menu">
                                      <li><a href="http://127.0.0.1:8000/admin/auth/users?_export_=all" target="_blank">All</a></li>
                                      <li><a href="http://127.0.0.1:8000/admin/auth/users?_export_=page%3A1" target="_blank">Current page</a></li>
                                      <li><a href="http://127.0.0.1:8000/admin/auth/users?_export_=selected%3A__rows__" target="_blank" class="export-selected">Selected rows</a></li>
                                  </ul>
                              </div>  
                        </div>

                         
                        <table class="table table-hover grid-table" id="financialTable">
                            <thead>
                              <tr> 
                                <th>Master Account Name</th>
                                <th>Master Account ID</th>
                                <th>Sub Account Name</th>
                                <th>Sub Account ID</th>
                                <th>SPENT in ARS</th>
                                <th>Spent in USD</th>
                                <th>Discount </th>
                                <th>Revenue</th>
                                <th>Google Media Cost</th>
                                <th>PlusM Share</th>
                                <th>Total Cost </th>
                                <th>Net Income </th>
                                <th>Net Income %</th> 
                              </tr>
                            </thead> 
                        </table>
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
 

    <script>
      var current_page = "GoogleAds dashboard";
      const __csrf_token = "{{@csrf_token()}}";
    </script> 

    <script src="{{asset('js/google_ads/google_ads_manager.js')}}"></script>
    <script src="{{asset('js/google_ads/financial_report.js')}}"></script>
@endsection
