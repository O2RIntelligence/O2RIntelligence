<?php

namespace App\Admin\Controllers;

use App\Admin\Models\AdminConfig;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Admin\Models\Administrator;
Use Encore\Admin\Admin;

class ReportController extends Controller
{
    public function index($report = null, Content $content)
    {

        $title = (!$report) ? 'Custom Reports' : ucfirst($report) . " Reports";
        $view = (!$report) ? 'reports' : $report;
        if($report == 'sources') $title = 'Media Source Report';
        if(!in_array($report, ['income', 'overall', 'sources', null])) abort(404);

        if(!$report)
            Admin::js('js/reports.js');
        if($report == 'sources')
            Admin::js('js/media_sources.js');

        $user = Auth::user();
        // optional
        $content->header($title);

        // add breadcrumb since v1.5.7
        $content->breadcrumb(
            ['text' => $title]
        );
        $seats = [];
        if($user->isRole('administrator') || ($user->isRole('reporter') && !$report))
            $seats = Administrator::where('api_token', '!=' , '')->select(['id', 'name', 'excluded_channels', 'api_token', 'partner_fee'])->get()->keyBy("id")->toArray();
        elseif ($user->isRole('seat'))
            $seats = $user->seats->keyBy("id")->toArray();
        $content->view('dashboard/' . $view, compact('user', 'seats'));

        return $content;
    }

    /** Returns Ms Channel IDs
     * @return JsonResponse
     */
    public function getMsChannelIds(): JsonResponse
    {
        return response()->json(AdminConfig::select('name','value')->where('name','ms_channel_id')->first());
    }

}
