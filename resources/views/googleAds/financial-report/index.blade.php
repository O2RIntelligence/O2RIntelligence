@section('css')
    {{-- page css link here  --}}
     <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css">  
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

                         
                        <table class="table table-hover grid-table" id="dataTable">
                            <thead>
                              <tr> 
                                <th>Master Account Name</th>
                                <th>Master Account ID</th>
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
                             <tbody>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                              <tr>
                                <td>Acount name one</td>
                                <td>ID-123</td>
                                <td>ID-123-1</td>
                                <td>100</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>10%</td>
                              </tr>
                             </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </section>
    </div>
@endsection
@section('js') 
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script>
    <script>
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
          });
    </script>
@endsection
