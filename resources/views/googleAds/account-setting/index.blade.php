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
                                <div class="btn-group pull-right" style="margin-right: 10px">
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
                                </div> 
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
                            <!-- <tbody>
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
                            </tbody> -->
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
