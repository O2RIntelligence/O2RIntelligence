<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
Use Encore\Admin\Admin;
use Illuminate\Http\Request;

Encore\Admin\Form::forget(['map', 'editor']);

if(strpos(Route::current()->getName(), 'edit') === false) {
    Admin::disablePjax();
}

Admin::css('/css/custom.css');
Admin::css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
Admin::css('https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap.min.css');
Admin::css('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
Admin::js('https://cdn.jsdelivr.net/lodash/4.14.1/lodash.min.js');
Admin::js('https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js');
Admin::js('https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap.min.js');
Admin::js('https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js');
Admin::js('https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js');
Admin::js('js/adtelligent.js');
Admin::js('js/admin.js');
Admin::css("https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.11.3/b-2.0.1/b-colvis-2.0.1/b-html5-2.0.1/b-print-2.0.1/cr-1.5.5/date-1.1.1/datatables.min.css");
Admin::js("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js");
Admin::js("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js");
Admin::js("js/datatables.min.js");
Admin::js('https://cdn.datatables.net/plug-ins/1.11.3/api/sum().js');
Admin::css('https://cdn.datatables.net/searchbuilder/1.3.0/css/searchBuilder.dataTables.min.css');
Admin::js('https://cdn.datatables.net/searchbuilder/1.3.0/js/dataTables.searchBuilder.min.js');
