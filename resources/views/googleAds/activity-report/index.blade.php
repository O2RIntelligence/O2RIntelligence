@section('css')
    {{-- page css link here  --}}
     <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css"> 
    <style>
      #pieChart{
        height: 302px !important;
        width: auto !important
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
                Activity Report
                <small> </small>
            </h1> 
            <!-- breadcrumb start -->
            <ol class="breadcrumb" style="margin-right: 30px;">
                <li><a href="{{url('google-ads/dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">
                    Activity report
                </li>
            </ol> 
            <!-- breadcrumb end --> 
        </section>

        <section class="content"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="box grid-box">
                       

                         
                        <table class="table table-hover grid-table" id="dataTable">
                            <thead>
                              <tr>
                                <th class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-select-all" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>&nbsp;
                                </th>
                                <th class="column-id">ID <a class="fa fa-fw fa-sort" href="http://127.0.0.1:8000/admin/auth/users?_sort%5Bcolumn%5D=id&amp;_sort%5Btype%5D=desc"></a>
                                </th>
                                <th class="column-username">Username</th>
                                <th class="column-name">Name</th>
                                <th class="column-roles">Roles</th>
                                <th class="column-created_at">Created At</th>
                                <th class="column-updated_at">Updated At</th>
                                <th class="column-api_password">API Password</th>
                                <th class="column-partner_fee">Partner Fee</th>
                                <th class="column-__actions__">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr data-key="1">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="1" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 1 </td>
                                <td class="column-username"> admin </td>
                                <td class="column-name"> Administrator </td>
                                <td class="column-roles">
                                  <span class="label label-success">Manager</span>
                                </td>
                                <td class="column-created_at"> 2021-10-20 16:50:22 </td>
                                <td class="column-updated_at"> 2022-05-05 06:07:37 </td>
                                <td class="column-api_password"></td>
                                <td class="column-partner_fee"> 0 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/1/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/1">Show</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="3">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="3" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 3 </td>
                                <td class="column-username"> o2r_api@o2rintelligence.com </td>
                                <td class="column-name"> O2R </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2021-10-21 22:31:28 </td>
                                <td class="column-updated_at"> 2022-05-13 19:30:02 </td>
                                <td class="column-api_password"> Smartyads_api_2021 </td>
                                <td class="column-partner_fee"> 0 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/3/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/3">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="3" href="javascript:void(0);" class="grid-row-action-62b58e69c4d4c4232">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="4">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="4" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 4 </td>
                                <td class="column-username"> shauking_api@o2rintelligence.com </td>
                                <td class="column-name"> Shauking </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2021-10-22 01:05:41 </td>
                                <td class="column-updated_at"> 2022-05-13 19:30:02 </td>
                                <td class="column-api_password"> Smartyads_api_2021 </td>
                                <td class="column-partner_fee"> 0 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/4/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/4">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="4" href="javascript:void(0);" class="grid-row-action-62b58e69c50c81068">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="5">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="5" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 5 </td>
                                <td class="column-username"> gabyads_api@o2rintelligence.com </td>
                                <td class="column-name"> GabyAds </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2021-10-22 01:07:20 </td>
                                <td class="column-updated_at"> 2022-05-13 19:30:03 </td>
                                <td class="column-api_password"> EbQj1k5K1rkak82 </td>
                                <td class="column-partner_fee"> 30 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/5/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/5">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="5" href="javascript:void(0);" class="grid-row-action-62b58e69c53735736">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="6">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="6" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 6 </td>
                                <td class="column-username"> TQ_api@o2rintelligence.com </td>
                                <td class="column-name"> TQ </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2021-10-22 01:07:47 </td>
                                <td class="column-updated_at"> 2022-06-23 04:30:03 </td>
                                <td class="column-api_password"> 9KcArUE1YcgQQmb </td>
                                <td class="column-partner_fee"> -50 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/6/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/6">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="6" href="javascript:void(0);" class="grid-row-action-62b58e69c57f34682">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="11">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="11" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 11 </td>
                                <td class="column-username"> metafox_api@o2rintelligence.com </td>
                                <td class="column-name"> MetaFox </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2021-12-01 22:43:16 </td>
                                <td class="column-updated_at"> 2022-05-13 19:30:04 </td>
                                <td class="column-api_password"> 29Z3Ry74xyFktS- </td>
                                <td class="column-partner_fee"> 50 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/11/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/11">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="11" href="javascript:void(0);" class="grid-row-action-62b58e69c5b6a1355">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="12">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="12" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 12 </td>
                                <td class="column-username"> reporter </td>
                                <td class="column-name"> Reporter </td>
                                <td class="column-roles">
                                  <span class="label label-success">Account Manager</span>
                                </td>
                                <td class="column-created_at"> 2021-12-21 01:10:01 </td>
                                <td class="column-updated_at"> 2021-12-21 01:24:14 </td>
                                <td class="column-api_password"></td>
                                <td class="column-partner_fee"> 0 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/12/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/12">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="12" href="javascript:void(0);" class="grid-row-action-62b58e69c5e4e1628">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="13">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="13" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 13 </td>
                                <td class="column-username"> oshi_api@o2rintelligence.com </td>
                                <td class="column-name"> OshiMedia-HQ </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2022-01-27 03:29:04 </td>
                                <td class="column-updated_at"> 2022-05-27 01:00:04 </td>
                                <td class="column-api_password"> 4czs6XwKi0uJVey </td>
                                <td class="column-partner_fee"> 0 </td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/13/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/13">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="13" href="javascript:void(0);" class="grid-row-action-62b58e69c60ec7347">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                              <tr data-key="14">
                                <td class="column-__row_selector__">
                                  <div class="icheckbox_minimal-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                    <input type="checkbox" class="grid-row-checkbox" data-id="14" autocomplete="off" style="position: absolute; opacity: 0;">
                                    <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                  </div>
                                </td>
                                <td class="column-id"> 14 </td>
                                <td class="column-username"> Bgreenwalt </td>
                                <td class="column-name"> Brian Greenwalt </td>
                                <td class="column-roles">
                                  <span class="label label-success">Partner</span>
                                </td>
                                <td class="column-created_at"> 2022-04-28 12:44:31 </td>
                                <td class="column-updated_at"> 2022-05-15 22:51:58 </td>
                                <td class="column-api_password"></td>
                                <td class="column-partner_fee"></td>
                                <td class="column-__actions__">
                                  <div class="grid-dropdown-actions dropdown">
                                    <a href="#" style="padding: 0 10px;" class="dropdown-toggle" data-toggle="dropdown">
                                      <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" style="min-width: 70px !important;box-shadow: 0 2px 3px 0 rgba(0,0,0,.2);border-radius:0;left: -65px;top: 5px;">
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/14/edit">Edit</a>
                                      </li>
                                      <li>
                                        <a href="http://127.0.0.1:8000/admin/auth/users/14">Show</a>
                                      </li>
                                      <li>
                                        <a data-_key="14" href="javascript:void(0);" class="grid-row-action-62b58e69c638a2961">Delete</a>
                                      </li>
                                    </ul>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>  
                </div>
            </div> 
        </section>

        <section class="content">
          <div class="row">
            <div class="col-md-6"> 
    
              <!-- DONUT CHART -->
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Donut Chart</h3>
    
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body text-center">
                  <canvas id="pieChart" style="height:250px"></canvas>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
    
            </div>
            <!-- /.col (LEFT) -->
            <div class="col-md-6">
              <!-- LINE CHART -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Line Chart</h3>
    
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <div class="chart">
                    <canvas id="lineChart" style="height:302px"></canvas>
                  </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
    
              <!-- BAR CHART -->
               
              <!-- /.box -->
    
            </div>
            <!-- /.col (RIGHT) -->
          </div>
          <!-- /.row -->
    
        </section>


    </div>
@endsection
@section('js') 
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script> 
    <script src="{{asset('new-dashboard/chart.js')}}"></script>  
 
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
<script>
  $(function () {
    /* ChartJS
     * -------
     * Here we will create a few charts using ChartJS
     */

    //--------------
    //- AREA CHART -
    //--------------

    // Get context with jQuery - using jQuery's .get() method.
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    // This will get the first returned node in the jQuery collection.
    var areaChart = new Chart(lineChartCanvas);

    var areaChartData = {
      labels: ["January", "February", "March", "April", "May", "June", "July"],
      datasets: [
        {
          label: "Electronics",
          fillColor: "rgba(210, 214, 222, 1)",
          strokeColor: "rgba(210, 214, 222, 1)",
          pointColor: "rgba(210, 214, 222, 1)",
          pointStrokeColor: "#c1c7d1",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(220,220,220,1)",
          data: [65, 59, 80, 81, 56, 55, 40]
        },
        {
          label: "Digital Goods",
          fillColor: "rgba(60,141,188,0.9)",
          strokeColor: "rgba(60,141,188,0.8)",
          pointColor: "#3b8bba",
          pointStrokeColor: "rgba(60,141,188,1)",
          pointHighlightFill: "#fff",
          pointHighlightStroke: "rgba(60,141,188,1)",
          data: [28, 48, 40, 19, 86, 27, 90]
        }
      ]
    };

    var areaChartOptions = {
      //Boolean - If we should show the scale at all
      showScale: true,
      //Boolean - Whether grid lines are shown across the chart
      scaleShowGridLines: false,
      //String - Colour of the grid lines
      scaleGridLineColor: "rgba(0,0,0,.05)",
      //Number - Width of the grid lines
      scaleGridLineWidth: 1,
      //Boolean - Whether to show horizontal lines (except X axis)
      scaleShowHorizontalLines: true,
      //Boolean - Whether to show vertical lines (except Y axis)
      scaleShowVerticalLines: true,
      //Boolean - Whether the line is curved between points
      bezierCurve: true,
      //Number - Tension of the bezier curve between points
      bezierCurveTension: 0.3,
      //Boolean - Whether to show a dot for each point
      pointDot: false,
      //Number - Radius of each point dot in pixels
      pointDotRadius: 4,
      //Number - Pixel width of point dot stroke
      pointDotStrokeWidth: 1,
      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
      pointHitDetectionRadius: 20,
      //Boolean - Whether to show a stroke for datasets
      datasetStroke: true,
      //Number - Pixel width of dataset stroke
      datasetStrokeWidth: 2,
      //Boolean - Whether to fill the dataset with a color
      datasetFill: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true
    };



    //Create the line chart
    areaChart.Line(areaChartData, areaChartOptions);

    //-------------
    //- LINE CHART -
    //--------------
    var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
    var lineChart = new Chart(lineChartCanvas);
    var lineChartOptions = areaChartOptions;
    lineChartOptions.datasetFill = false;
    lineChart.Line(areaChartData, lineChartOptions);




    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [
      {
        value: 700,
        color: "#f56954",
        highlight: "#f56954",
        label: "Chrome"
      },
      {
        value: 500,
        color: "#00a65a",
        highlight: "#00a65a",
        label: "IE"
      },
      {
        value: 400,
        color: "#f39c12",
        highlight: "#f39c12",
        label: "FireFox"
      },
      {
        value: 600,
        color: "#00c0ef",
        highlight: "#00c0ef",
        label: "Safari"
      },
      {
        value: 300,
        color: "#3c8dbc",
        highlight: "#3c8dbc",
        label: "Opera"
      },
      {
        value: 100,
        color: "#d2d6de",
        highlight: "#d2d6de",
        label: "Navigator"
      }
    ];
    var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: "#fff",
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: "easeOutBounce",
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
    };





    
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions); 
  });
</script>
@endsection
