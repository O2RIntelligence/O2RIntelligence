<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->resource('auth/users', UserController::class);
    $router->get('/reports/{report?}', 'ReportController@index')->name('reports');

    $router->get('environments/server-stats', 'EnvironmentController@statistics')->name('server-statistics');
    $router->post('environments/server-stats', 'EnvironmentController@servers');

    $router->resource('environments', EnvironmentController::class);

});
