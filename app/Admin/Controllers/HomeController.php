<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Auth;
use Carbon\Carbon;
use App\Admin\Models\Administrator;
Use Encore\Admin\Admin;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $user = Auth::user();
        if($user->isRole('reporter'))
            return redirect('/admin/reports');

        if($user->isRole('administrator') == false && $user->isRole('seat') == false)
            return redirect('/admin/reports/income');

        Admin::js('js/dashboard.js?v=0.7');

        // optional
        $title = 'Dashboard';

        $content->header($title);

        // add breadcrumb since v1.5.7
        $content->breadcrumb(
            ['text' => $title]
        );
        $seats = [];
        if($user->isRole('administrator'))
            $seats = Administrator::where('api_token', '!=' , '')->select(['id', 'name', 'excluded_channels', 'api_token', 'partner_fee'])->get()->keyBy("id")->toArray();
        elseif ($user->isRole('seat'))
            $seats = $user->seats->keyBy("id")->toArray();

        $content->view('dashboard/index', compact('user', 'seats'));

        return $content;
    }
}
