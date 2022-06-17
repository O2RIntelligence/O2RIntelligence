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
                ->withClientId('427262788972-qrdrnh2tqk4tdh0irnshnu6p1l6emcfb.apps.googleusercontent.com')
                ->withClientSecret('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')
                ->withRefreshToken('1//04Rb0dFn_vHLRCgYIARAAGAQSNwF-L9IrI5r-6wV4cCwcU5BO06kYP_Ot30FBAKR8w3h4mmBv5obwAGogULpj6RrFkXg0ViFMiFc')
                ->build();

            $googleAdsClient = (new GoogleAdsClientBuilder())
                ->withOAuth2Credential($oAuth2Credential)
                ->withDeveloperToken('4Z7nw1N2229CNuPpKNi9LA')
                ->withLoginCustomerId('6432926678')
                ->build();
            return $googleAdsClient;
        } catch (Exception $e) {

        }
    }
}