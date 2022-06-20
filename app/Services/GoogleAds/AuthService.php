<?php

namespace App\Services\GoogleAds;


use App\Model\GoogleAds\GoogleGrantToken;
use Exception;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClientBuilder;
use Google\Client;

class AuthService
{

    private $clientID;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        $this->clientID = config('googleAds.client_id');
        $this->clientSecret = config('googleAds.client_secret');
        $this->redirectUri = config('googleAds.redirect_uri');
    }


    /**
     * get google client object
     */
    public function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Google Ads O2R');
        $client->setScopes('https://www.googleapis.com/auth/adwords');
        $client->setClientId($this->clientID);
        $client->setClientSecret($this->clientSecret);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(url($this->redirectUri));
        return $client;
    }

    /**
     * get service
     * @param $email
     * @throws Exception
     */
    public function getGmailService($email)
    {
        if($googleUser = GoogleUser::query()->where('email',$email)->first()) {
            $token = $googleUser->auth_token;

            $client = $this->getClient();
            $client->setAccessToken($token);
            if ($client->isAccessTokenExpired()) {
                $token = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                if (!$googleUser->update(['auth_token' => json_encode($token)])) {
                    throw new Exception('Could not Update Token with refresh token expired');
                }
                $client->setAccessToken($token);
            }//else token is not expired
        }else{
            throw new Exception('Could not find a Google user');
        }
        return new Google_Service_Gmail($client);
    }

    /**
     * get google ads service
     * @throws Exception
     */
    public function getGoogleAdsService($masterAccount)
    {
        try {
            $tokens = GoogleGrantToken::get()->first();
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId($this->clientID)
                ->withClientSecret( $this->clientSecret)
                ->withRefreshToken($tokens->refresh_token)
                ->build();

            return (new GoogleAdsClientBuilder())
                ->withOAuth2Credential($oAuth2Credential)
                ->withDeveloperToken($masterAccount->developer_token)
                ->withLoginCustomerId($masterAccount->account_id)
                ->build();
        } catch (Exception $exception) {
            return  $exception;
        }
    }
}