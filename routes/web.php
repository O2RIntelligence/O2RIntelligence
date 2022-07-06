<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\GoogleAds\ActivityReportController;
use App\Http\Controllers\GoogleAds\DailyDataController;
use App\Http\Controllers\GoogleAds\DashboardController;
use App\Http\Controllers\GoogleAds\FinancialReportController;
use App\Http\Controllers\GoogleAds\GeneralVariableController;
use App\Http\Controllers\GoogleAds\GoogleLoginController;
use App\Http\Controllers\GoogleAds\HourlyDataController;
use App\Http\Controllers\GoogleAds\MasterAccountController;
use App\Http\Controllers\GoogleAds\SubAccountController;
use App\Services\GoogleAds\GoogleAdsService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

//Route::group(['middleware'=>'auth'],function (){

//Master Account Operations
Route::post('/google-ads/master-account/store', [MasterAccountController::class, 'store'])->name('google-ads.master-account.store');
Route::post('/google-ads/master-account/show', [MasterAccountController::class, 'show'])->name('google-ads.master-accounts.show');
Route::get('/google-ads/master-accounts', [MasterAccountController::class, 'getAll'])->name('google-ads.master-accounts.get');
Route::post('/google-ads/master-account/update', [MasterAccountController::class, 'update'])->name('google-ads.master-account.update');
Route::post('/google-ads/master-account/status', [MasterAccountController::class, 'switchStatus'])->name('google-ads.master-account.status');
Route::post('/google-ads/master-account/delete', [MasterAccountController::class, 'delete'])->name('google-ads.master-account.delete');
Route::post('/google-ads/master-account/check-access', [MasterAccountController::class, 'checkIfDeveloperTokenIsUpdated'])->name('google-ads.master-account.check-access');


//Sub-Account Operations
Route::get('/google-ads/sub-accounts/online', [SubAccountController::class, 'getSubAccountsFromGoogleAds'])->name('google-ads.sub-accounts.online');
Route::get('/google-ads/sub-accounts', [SubAccountController::class, 'showAll'])->name('google-ads.sub-accounts.get');

//Cost Operation
Route::post('/google-ads/sub-accounts/daily-data', [DailyDataController::class, 'getDailyData'])->name('google-ads.sub-accounts.daily-data');
Route::get('/google-ads/sub-accounts/hourly-data', [HourlyDataController::class, 'getHourlyData'])->name('google-ads.sub-accounts.hourly-data');

//General Variables Operation
Route::post('/google-ads/general-variable/store', [GeneralVariableController::class, 'store'])->name('google-ads.general-variable.store');
Route::post('/google-ads/general-variable/show', [GeneralVariableController::class, 'show'])->name('google-ads.general-variable.show');
Route::get('/google-ads/general-variables', [GeneralVariableController::class, 'getAll'])->name('google-ads.general-variables.get');
Route::post('/google-ads/general-variable/update', [GeneralVariableController::class, 'update'])->name('google-ads.general-variable.update');
Route::post('/google-ads/general-variable/status', [GeneralVariableController::class, 'switchStatus'])->name('google-ads.general-variable.status');
Route::post('/google-ads/general-variable/delete', [GeneralVariableController::class, 'delete'])->name('google-ads.general-variable.delete');

//Dashboard API's
Route::post('/google-ads/dashboard/data', [DashboardController::class, 'getAllDashboardData'])->name('google-ads.dashboard.data');
Route::get('/google-ads/all-accounts/data',  [DashboardController::class, 'getAccountInformation'])->name('googleAds.accounts.index');



//Google OAuth2 Login
Route::get('/login/google', [GoogleLoginController::class, 'loginWithGoogle'])->name('login.google');

//});
Route::get('/login/google/callback', [GoogleLoginController::class, 'callBackHandler'])->name('login.google.callback');

//USD Rate From RapidAPI
Route::get('/google-ads/usdRate', [GoogleAdsService::class, 'getUsdRate'])->name('google-ads.usd-rate');


//googleAds Web Paths
Route::get('admin/google-ads/dashboard', [DashboardController::class, 'index'])->name('google-ads.dashboard.index');
Route::get('admin/google-ads/activity-report', [ActivityReportController::class, 'index'])->name('googleAds.activity-report.index');
Route::get('admin/google-ads/financial-report',  [FinancialReportController::class, 'index'])->name('googleAds.financial-report.index');
Route::get('admin/google-ads/account-setting',  [MasterAccountController::class, 'index'])->name('googleAds.account-setting.index');
Route::get('admin/google-ads/general-variable', [GeneralVariableController::class, 'index'])->name('googleAds.general-variable.index');


