<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{--    Css links here--}}
    <link rel="stylesheet" href="{{asset('css/custom.css')}}">
{{--   <link rel="stylesheet" href="/css/custom.css');--}}
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css">
   <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.5/date-1.1.1/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.dataTables.min.css">


    <link rel="stylesheet" href="http://localhost/o2r/public/css/custom.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.5/date-1.1.1/datatables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.dataTables.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/iCheck/all.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.3.0/dist/themes/light.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/bootstrap-fileinput/css/fileinput.min.css?v=4.5.2">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/select2/select2.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/ionslider/ion.rangeSlider.skinNice.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/dist/css/skins/skin-blue-light.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/laravel-admin/laravel-admin.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/nprogress/nprogress.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/sweetalert2/dist/sweetalert2.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/nestable/nestable.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/google-fonts/fonts.css">
    <link rel="stylesheet" href="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/dist/css/AdminLTE.min.css">


    <script src="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>


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


{{--Master Js here--}}
<script src="http://localhost/o2r/public/vendor/laravel-admin/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>


<script src="https://cdn.jsdelivr.net/lodash/4.14.1/lodash.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
{{--<script src="{{asset('js/adtelligent.js')}}"></script>--}}
{{--<script src="{{asset('js/admin.js')}}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
{{--<script src="{{asset('js/datatables.min.js')}}"></script>--}}
<script src="https://cdn.datatables.net/plug-ins/1.11.3/api/sum().js"></script>
<script src="https://cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js"></script>

@yield('js')

</body>
</html>