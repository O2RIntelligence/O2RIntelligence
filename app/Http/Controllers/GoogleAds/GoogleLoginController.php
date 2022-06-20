<?php

namespace App\Http\Controllers\GoogleAds;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Google\Client;
use Google_Service_Oauth2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Google Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling login with Google, callback methods
    |
    */

    /**
     * // This Method handles the redirected url with keys sent from Google
     * @param Request $request
     * @return array|string
     */
    public function callBackHandler(Request $request)
    {
        try {
            $client = new Client();
            $code = $request->code;
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token['access_token']);

            return $token;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * This Method handles the URL for Google Login
     */
    public function loginWithGoogle()
    {
        $client = new Client();
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

}
