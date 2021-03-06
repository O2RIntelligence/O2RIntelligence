<?php

use App\Admin\Controllers\ReportController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get-ms-channel-ids',[ReportController::class,'getMsChannelIds'])->name('get.ms-channel-ids');
Route::get('/get-users',[ReportController::class,'getUsers'])->name('get.users');

