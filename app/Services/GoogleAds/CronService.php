<?php

namespace App\Services\GoogleAds;

use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsServerStreamDecorator;
use Google\ApiCore\ApiException;

Class CronService{


    public function getGoogleAdsClient(){
        return new AuthService::class;
    }
    public function fetchAll() {
        $customerId = 2540375170;//todo: from db
        $this->getMetrics($this->getGoogleAdsClient()->getGoogleAdsService(),$customerId);
    }

    /**
     * Runs the example.
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @param int $customerId the customer ID
     * @throws ApiException
     */
    public static function getMetrics(GoogleAdsClient $googleAdsClient, int $customerId)
    {
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
      $query ="SELECT metrics.cost_micros, segments.hour FROM campaign WHERE segments.date BETWEEN '".date('Y-m-d')."' AND '".date('Y-m-d')."' AND customer.id = 2540375170 AND metrics.cost_micros > 0";

        /** @var GoogleAdsServerStreamDecorator $stream */
        $stream =
            $googleAdsServiceClient->searchStream($customerId, $query);

        // Iterates over all rows in all messages and prints the requested field values for
        // the keyword in each row.
        $results = [];
        $totalCost = 0;
        foreach ($stream->iterateAllElements() as $key=>$googleAdsRow) {
            $results[] = json_decode($googleAdsRow->serializeToJsonString(), true);
            $totalCost += $results[$key]['metrics']['costMicros'];

        }
        return(json_encode(array($results, $totalCost/1000000)));
    }

}