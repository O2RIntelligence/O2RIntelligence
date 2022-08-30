<?php

use App\Admin\Controllers\ReportController;
use App\Http\Controllers\GoogleAds\ActivityReportController;
use App\Http\Controllers\GoogleAds\FinancialReportController;
use App\Http\Controllers\PixalateImpressionController;
use App\Model\PixalateImpression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/get-serving-fee',[ReportController::class,'getServingFee'])->name('get.ms-channel-ids');
Route::get('/get-users',[ReportController::class,'getUsers'])->name('get.users');

//Activity Report operations
Route::post('/google-ads/activity-report/data', [ActivityReportController::class, 'getAllActivityReportData'])->name('google-ads.activity-report.data');
Route::get('/google-ads/activity-report', [ActivityReportController::class, 'index'])->name('google-ads.activity-report.index');

//Financial Report operations
Route::post('/google-ads/financial-report/data', [FinancialReportController::class, 'getAllFinancialReportData'])->name('google-ads.financial-report.data');
Route::get('/google-ads/financial-report', [FinancialReportController::class, 'index'])->name('google-ads.financial-report.index');

//pixalate data
Route::get('/pixalate', [ReportController::class, 'getPixalateData'])->name('pixalate.get');

//insert raw pixalate data
//Route::post('/raw-pixalate', [PixalateImpressionController::class, 'insertRawPixalateData'])->name('pixalate.insert.raw');




