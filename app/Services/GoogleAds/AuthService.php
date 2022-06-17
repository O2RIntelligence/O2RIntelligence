<?php

namespace App\Services\GoogleAds;


class AuthService
{

    /**
     * get google client object
     * @return Google_Client
     */
    public function getClient(): Google_Client
    {
        $client = new Google_Client();
        $client->setApplicationName('Gmail API for CRM');
        $client->setScopes('GoogleAdsScopes');
        $client->setClientId($this->appId);
        $client->setClientSecret($this->appSecret);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(url($this->redirectUri));
        return $client;
    }

    /**
     * get service
     * @param $email
     * @return Google_Service_Gmail
     * @throws Exception
     */
    public function getGoogleAdsService()
    {
        try {
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId('')
                ->withClientSecret('')
                ->withRefreshToken('')
                ->build();

            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withOAuth2Credential($oAuth2Credential)
                ->withDeveloperToken('')
                ->withLoginCustomerId('6432926678')
                ->build();
            return $googleAdsClient;
        } catch (Exception $e) {

        }
    }
}