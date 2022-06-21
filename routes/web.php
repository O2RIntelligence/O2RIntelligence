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

use App\Http\Controllers\GoogleAds\GeneralVariableController;
use App\Http\Controllers\GoogleAds\GoogleLoginController;
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


//Sub-Account Operations
Route::get('/google-ads/sub-accounts/online', [SubAccountController::class, 'getSubAccountsFromGoogleAds'])->name('google-ads.sub-accounts.online');
Route::get('/google-ads/sub-accounts', [SubAccountController::class, 'showAll'])->name('google-ads.sub-accounts.get');

//Cost Operation
Route::get('/google-ads/sub-accounts/cost', [SubAccountController::class, 'totalCost'])->name('google-ads.sub-accounts.cost');

//General Variables Operation
Route::post('/google-ads/general-variable/store', [GeneralVariableController::class, 'store'])->name('google-ads.general-variable.store');
Route::post('/google-ads/general-variable/show', [GeneralVariableController::class, 'show'])->name('google-ads.general-variable.show');
Route::get('/google-ads/general-variables', [GeneralVariableController::class, 'getAll'])->name('google-ads.general-variables.get');
Route::post('/google-ads/general-variable/update', [GeneralVariableController::class, 'update'])->name('google-ads.general-variable.update');
Route::post('/google-ads/general-variable/status', [GeneralVariableController::class, 'switchStatus'])->name('google-ads.general-variable.status');
Route::post('/google-ads/general-variable/delete', [GeneralVariableController::class, 'delete'])->name('google-ads.general-variable.delete');

//Google OAuth2 Login
Route::get('/login/google', [GoogleLoginController::class, 'loginWithGoogle'])->name('login.google');

//});
Route::get('/login/google/callback', [GoogleLoginController::class, 'callBackHandler'])->name('login.google.callback');

Route::get('/google-ads/usdRate', [GoogleAdsService::class, 'getUsdRate'])->name('google-ads.usd-rate');

