<?php

namespace App\Admin\Controllers;

use App\Admin\Models\Environment;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Auth;
use App\Admin\Models\Administrator;
Use Encore\Admin\Admin;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class EnvironmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Environment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Environment());

        // $grid->column('id', __('Id'));
        $grid->column('remote_id', __('Remote ID'));
        $grid->column('ip', __('IP Address'));
        $grid->column('status', __('Status'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Environment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ip', __('IP Address'));
        $show->field('remote_id', __('Remote id'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Environment());

        $form->ip('ip', __('Ip'));
        $form->number('remote_id', __('Remote id'));
        $form->select('status', __('Last Status'))->options(['active' => 'Active', 'error' => 'Errored']);

        return $form;
    }

    public function servers(Request $request) {

        $user = Auth::user();

        if($user->isRole('administrator') == false)
            abort(403);

        $environmentId = $request->input('environmentId');
        $envrionment = Environment::find($environmentId);
        if(!$envrionment) abort(404);

        $type = $request->input('type');

        if(filter_var($envrionment->ip, FILTER_VALIDATE_IP) === false) abort(404);

        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://'. $envrionment->ip,
            'timeout'  => 30,
        ]);

        $headers = [
            'Authorization' => 'Basic TzJSOk8yUl8yMDIxIQ==',
            'Accept'        => 'application/json',
        ];

        $stats = [];

        if($type == 'single') {
            $stats['ByProxy']  = $client->request('GET', '/Statstic/GetStatByProxy', [
                'headers' =>  $headers
            ]);
            $stats['ByServer'] = $client->request('GET', '/Statstic/GetStatByServer', [
                'headers' =>  $headers
            ]);
            $stats['Monthly']  =  $client->request('GET', '/Statstic/Getmonthlyprofit', [
                'headers' =>  $headers
            ]);
            // $stat['ByServer'] =
        } else if ( $type == 'realtime') {
            $intervals = ["day" => 1, "hour" => 2, "5m" => 3, "1m" => 4];

            foreach ($intervals as $key => $interval) {
                if(!is_numeric($interval))
                    abort(404);

                $stats[$key] = $client->request('GET', '/Statstic/GetStat' . (int) $interval, [
                    'headers' => $headers
                ]);
            }

        } else {
            abort(404);
        }

        $stats = array_map( function($v) {
            return \json_decode($v->getBody(), true);
        }, $stats);

        $filterServers = function (&$response) {
            $response = array_values(array_filter( $response, function($value) {
                return $value['ImpCount'];
            }));
        };

        if( isset($stats['ByProxy']) ) {
            $filterServers($stats['ByProxy']);
            // $filterServers($stats['ByServer']);
        }


        echo json_encode($stats);
    }

    public function statistics(Content $content) {

        $user = Auth::user();
        if($user->isRole('administrator') == false)
            abort(403);

        Admin::js('js/server-statistics.js?v=0.1');

        // optional
        $title = 'Servers Statistics';

        $content->header($title);

        // add breadcrumb since v1.5.7
        $content->breadcrumb(
            ['text' => $title]
        );

        $seats = Administrator::where('api_token', '!=' , '')->select(['id', 'name', 'excluded_channels', 'api_token', 'partner_fee'])->get()->keyBy("id")->toArray();
        $environments = Environment::all()->toArray();

        $content->view('dashboard/server-stats', compact('user', 'seats', 'environments'));

        return $content;
    }

}
