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
    public function getClient(): Client
    {
        $client = new Client();
        $client->setApplicationName('Google Ads O2R Intelligence');
        $client->setScopes('https://www.googleapis.com/auth/adwords');
        $client->setClientId($this->clientID);
        $client->setClientSecret($this->clientSecret);
        $client->setAccessType('offline');
        $client->setPrompt('none');
        $client->setRedirectUri(url($this->redirectUri));
        return $client;
    }

    /**
     * get Google Ads service also checks if refresh_token is Expired
     * @param $masterAccount
     * @return GoogleAdsClient
     * @throws Exception
     */
    public function getGoogleAdsService($masterAccount): GoogleAdsClient
    {
        try {
            if ($googleGrantTokens = GoogleGrantToken::get()->first()->toArray()) {
                $client = $this->getClient();
                $client->setAccessToken($googleGrantTokens);
                $googleGrantTokens = (object)$googleGrantTokens;
                if (empty($googleGrantTokens->access_token) || $client->isAccessTokenExpired()) {
                    $token = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
//                    if (!GoogleGrantToken::where('id',$googleGrantTokens->id)->update(['auth_token' => json_encode($token)])) {
//                        throw new Exception('Could not Update Token with refresh token expired');
//                    }
                    $client->setAccessToken($token);
                    $token = (object) $token;
                    GoogleGrantToken::where('id',$googleGrantTokens->id)->update(['refresh_token'=>$token->refresh_token, 'access_token'=>$token->access_token, 'expires_in'=>$token->expires_in]);
                }else $token = $googleGrantTokens;
                $oAuth2Credential = (new OAuth2TokenBuilder())
                    ->withClientId($this->clientID)
                    ->withClientSecret($this->clientSecret)
                    ->withRefreshToken($token->access_token)
                    ->build();

                return (new GoogleAdsClientBuilder())
                    ->withOAuth2Credential($oAuth2Credential)
                    ->withDeveloperToken($masterAccount->developer_token)
                    ->withLoginCustomerId($masterAccount->account_id)
                    ->build();
            } else {
                throw new Exception('Could not find any tokens associated');
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }



}