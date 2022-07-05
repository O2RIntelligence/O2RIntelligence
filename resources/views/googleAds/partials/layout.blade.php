<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GoogleAds Dashboard</title>
    {{--    Css links here--}}

   <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
   <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
 
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css"> 
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('new-dashboard/plugin/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('//cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{asset('//cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.3.0/dist/themes/light.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/bootstrap-fileinput/css/fileinput.min.css?v=4.5.2')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.skinNice.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/dist/css/skins/skin-blue-light.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/laravel-admin/laravel-admin.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/nprogress/nprogress.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/sweetalert2/dist/sweetalert2.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/nestable/nestable.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/toastr/build/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/bootstrap3-editable/css/bootstrap-editable.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/google-fonts/fonts.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('new-dashboard/new-dashboard.main.css')}}">


    <script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js')}}"></script>


    @yield('css')
</head>

<body class="hold-transition skin-blue-light sidebar-mini">
<div class="wrapper" >
    @include('googleAds.partials.header')
    @include('googleAds.partials.sidebar')

    <div class="content-wrapper" id="pjax-container" style="min-height: 860px;">
        @yield('content')
    </div>

    @include('googleAds.partials.footer')
</div>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/dist/js/app.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/jquery-pjax/jquery.pjax.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/nprogress/nprogress.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/nestable/jquery.nestable.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/toastr/build/toastr.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/bootstrap3-editable/js/bootstrap-editable.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/sweetalert2/dist/sweetalert2.min.js')}}"></script>
{{-- <script src="{{asset('vendor/laravel-admin-ext/chartjs/Chart.bundle.min.js')}}"></script> --}}
<script src="//cdn.jsdelivr.net/lodash/4.14.1/lodash.min.js"></script>

 

{{-- <script src="//cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script> --}}
<script src="{{asset('js/adtelligent.js')}}"></script>
<script src="{{asset('js/admin.js')}}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
{{-- <script src="//cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
<script src="//cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script> --}}
<script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/iCheck/icheck.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/input-mask/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/moment/min/moment-with-locales.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('new-dashboard/plugin/daterangepicker/daterangepicker.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="//cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script>
<script src="//npmcdn.com/flatpickr@4.6.6/dist/l10n/zh.js"></script>
<script src="{{asset('vendor/laravel-admin/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/bootstrap-fileinput/js/fileinput.min.js?v=4.5.2')}}"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/number-input/bootstrap-number-input.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/bootstrap-switch/dist/js/bootstrap-switch.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js')}}"></script>
<script src="{{asset('vendor/laravel-admin/bootstrap-fileinput/js/plugins/sortable.min.js?v=4.5.2')}}"></script>
<script src="{{asset('vendor/laravel-admin/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>
@yield('js')

</body>
</html>