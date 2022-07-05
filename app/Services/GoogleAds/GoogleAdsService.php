<?php

namespace App\Services\GoogleAds;

use App\Http\Controllers\GoogleAds\HourlyDataController;
use App\Model\GoogleAds\DailyData;
use App\Model\GoogleAds\HourlyData;
use Exception;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V10\GoogleAdsServerStreamDecorator;
use Google\Ads\GoogleAds\V10\Resources\Customer;
use Google\Ads\GoogleAds\Lib\V10\ServiceClientFactoryTrait;
use Google\Ads\GoogleAds\V10\Resources\CustomerClient;
use Google\Ads\GoogleAds\V10\Services\CustomerServiceClient;
use Google\Ads\GoogleAds\V10\Services\GoogleAdsRow;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class GoogleAdsService
{
    private static $rootCustomerClients;
    private $hierarchy = [];

    /**
     * @return AuthService
     */
    public function getGoogleAdsClient()
    {
        return new AuthService();
    }

    /**
     * getAccountInformation.
     *
     * @param int $customerId the customer ID
     * @return false|string
     * @throws Exception
     */
    public function getSingleAccountInformation(int $customerId)
    {
        $googleAdsClient = $this->getGoogleAdsClient()->getGoogleAdsService();
        // Creates a query that retrieves the specified customer.
        $query = 'SELECT customer.id, '
            . 'customer.descriptive_name, '
            . 'customer.currency_code, '
            . 'customer.time_zone, '
            . 'FROM customer '
            . 'LIMIT 1';
        // Issues a search request to get the Customer object from the single row of the response
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        /** @var Customer $customer */
        $customer = $googleAdsServiceClient->search($customerId, $query)
            ->getIterator()
            ->current()
            ->getCustomer();
        $results[] = json_decode($customer->serializeToJsonString(), true);
        return json_encode($results);
    }

    /**
     * Gets Daily Data of all sub accounts
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @param $masterAccount
     * @param $subAccount
     * @param $dateRange
     * @param $generalVariable
     * @return array
     * @throws ApiException
     * @throws Exception
     */
    public function getDailyData(GoogleAdsClient $googleAdsClient, $masterAccount, $subAccount, $dateRange, $generalVariable)
    {
        $customerId = $subAccount->account_id;
        $discount = intval($masterAccount->discount);
        $usdToArs = intval($masterAccount->revenue_conversion_rate) > 0 ? $masterAccount->revenue_conversion_rate : $this->getUsdRate();
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        $official_dollar = intval($generalVariable->official_dollar);
        $plusMDiscount = intval($generalVariable->plus_m_discount);
        // Creates a query that retrieves all keyword statistics.
        $query = "SELECT metrics.cost_micros, segments.date,campaign_budget.amount_micros FROM campaign WHERE segments.date BETWEEN '" . $dateRange['startDate'] . "' AND '" . $dateRange['endDate'] . "' AND customer.id = " . $customerId." ORDER BY segments.date";//AND metrics.cost_micros > 0";
        // Issues a search stream request.
        /** @var GoogleAdsServerStreamDecorator $stream */
        $stream = $googleAdsServiceClient->searchStream($customerId, $query);
        // Iterates over all rows in all messages and prints the requested field values for the keyword in each row.
        $results = [];
        $formattedData = [];
        $costThisMonth = 0;
        $newData = [];

//        DailyData::query()->forceDelete();

        foreach ($stream->iterateAllElements() as $key => $googleAdsRow) {
            $results[] = json_decode($googleAdsRow->serializeToJsonString(), true);

            $cost = $results[$key]['metrics']['costMicros'] / config('googleAds.micro_cost');
            $date = $results[$key]['segments']['date'];
            $costInUsd = $cost / $usdToArs;
            $googleMediaCost = ($cost + ($cost * config('googleAds.google_media_cost_constant'))); //[SPENT in ARS+(SPENT in ARS x 0.21)]/Blue Dollar
            if (intval($generalVariable->blue_dollar) > 0) {
                $googleMediaCost = $googleMediaCost/ $generalVariable->blue_dollar;
            }
            //$plusMShare = ((($googleMediaCost - ($googleMediaCost * $plusMDiscount)) / $official_dollar) - $googleMediaCost) / 2; //[((Google Media Cost-(Google Media Cost*PLUSM Discount))/Official Dollar)-(Google Media Cost)]/2
            $plusMShare = $googleMediaCost;
            if($plusMDiscount>0)$plusMShare = $plusMShare - ($googleMediaCost * $plusMDiscount);
            if($official_dollar>0) $plusMShare = $plusMShare/$official_dollar;
            $plusMShare = $plusMShare - $googleMediaCost;
            $revenue = $costInUsd ; //Spent in USD - (Spent in USD X Discount)
            if($discount>0){
                $revenue = $revenue - ($costInUsd * $discount);
            }


            $currentMonth = intval(date('m',strtotime($date)));
            $currentMonthCurrentDay = intval(date('d',strtotime($date)));
            $currentMonthAllDayCount = intval(date('t',strtotime($date)));
//            $prevMonth = $currentMonth;
//            if($currentMonth > $prevMonth){
//                $costThisMonth = 0;
//                $prevMonth = $currentMonth;
//            }
            $total_cost = $googleMediaCost + $plusMShare;
            $costThisMonth = $cost;//todo:for multiple month add all cost<=>reset cost
            $account_budget = $results[$key]['campaignBudget']['amountMicros']/config('googleAds.micro_cost');
            $netIncome = $revenue - $total_cost;
            $netIncomePercent = (($revenue - $total_cost) / $costInUsd)*100;
            $accountBudgetPercent = ($costThisMonth / $account_budget)*100;
            $monthlyRunRate = ($cost/$currentMonthCurrentDay)*$currentMonthAllDayCount;
            if(!empty($newData['date']) && $newData['date']==$date && $newData['sub_account_id'] == $subAccount->id){
                $newData = [
                    'date' => $date,
                    'master_account_id' => $masterAccount->id,
                    'sub_account_id' => $subAccount->id,
                    'cost' => $cost+$newData['cost'],
                    'cost_usd' => $costInUsd+$newData['cost_usd'],
                    'discount' => $discount+$newData['discount'],
                    'revenue' => $revenue+$newData['revenue'],
                    'google_media_cost' => $googleMediaCost+$newData['google_media_cost'],
                    'plus_m_share' => $plusMShare+$newData['plus_m_share'],
                    'total_cost' => $total_cost+$newData['total_cost'],
                    'net_income' => $netIncome+$newData['net_income'],
                    'net_income_percent' => $netIncomePercent+$newData['net_income_percent'],
                    'account_budget' => $account_budget+$newData['account_budget'],
                    'budget_usage_percent' => $accountBudgetPercent+$newData['budget_usage_percent'],
                    'monthly_run_rate'=>$monthlyRunRate+$newData['monthly_run_rate'],
                ];
                array_pop($formattedData);
                $formattedData[] = $newData;
            }else {
                $newData = [
                    'date' => $date,
                    'master_account_id' => $masterAccount->id,
                    'sub_account_id' => $subAccount->id,
                    'cost' => $cost,
                    'cost_usd' => $costInUsd,
                    'discount' => $discount,
                    'revenue' => $revenue,
                    'google_media_cost' => $googleMediaCost,
                    'plus_m_share' => $plusMShare,
                    'total_cost' => $total_cost,
                    'net_income' => $revenue - $total_cost,
                    'net_income_percent' => (($revenue - $total_cost) / $costInUsd) * 100,
                    'account_budget' => $account_budget,
                    'budget_usage_percent' => ($costThisMonth / $account_budget) * 100,
                    'monthly_run_rate' => ($cost / $currentMonthCurrentDay) * $currentMonthAllDayCount,
                ];
                $formattedData[] = $newData;
            }
        }
//        return $results;
        $this->storeDailyData($formattedData);
        return $formattedData;
    }

    /**Stores Daily Data to Database
     * @throws Exception
     */
    public function storeDailyData($dailyData){
        foreach($dailyData as $singleData){
            $dailyData = DailyData::create($singleData);
            if(!$dailyData) throw new Exception('Could not create daily data');
        }
    }
    /**Stores Hourly Data to Database
     * @throws Exception
     */
    public function storeHourlyData($hourlyData){
        foreach($hourlyData as $singleData){
            HourlyData::where('hour', $singleData['hour'])->delete();
            $hourlyData = HourlyData::create($singleData);
            if(!$hourlyData) throw new Exception('Could not create hourly data');
        }
    }


    /** Gets Hourly Data of all sub accounts
     * @throws ApiException
     * @throws Exception
     */
    public function getHourlyData(GoogleAdsClient $googleAdsClient, $masterAccount, $subAccount, $dateRange)
    {
        //$dateRange=date('Y-m-d',strtotime("Yesterday"));
        $customerId = $subAccount->account_id;
        $usdToArs = intval($masterAccount->revenue_conversion_rate) > 0 ? $masterAccount->revenue_conversion_rate : $this->getUsdRate();
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();

        // Creates a query that retrieves all keyword statistics.
        $query = "SELECT metrics.cost_micros, segments.date, segments.hour FROM campaign WHERE segments.date BETWEEN '" . $dateRange. "' AND '" . $dateRange. "' AND customer.id = " . $customerId." ORDER BY segments.hour";//." AND metrics.cost_micros > 0";
        // Issues a search stream request.
        $stream = $googleAdsServiceClient->searchStream($customerId, $query);
        $results = [];
        $formattedData = [];
        $allData =[];
//        HourlyData::query()->forceDelete();
        // Iterates over all rows in all messages and prints the requested field values for the keyword in each row.
        foreach ($stream->iterateAllElements() as $key => $googleAdsRow) {
            $results[] = json_decode($googleAdsRow->serializeToJsonString(), true);
            $cost = $results[$key]['metrics']['costMicros'] / config('googleAds.micro_cost');
            $date = $results[$key]['segments']['date'];
            $hour = $results[$key]['segments']['hour'];
            $costInUsd = $cost / $usdToArs;

            $allData[] = [
                'date' => $date,
                'hour' => intval($hour),
                'master_account_id' => $masterAccount->id,
                'sub_account_id' => $subAccount->id,
                'cost' => $cost,
                'cost_usd' => $costInUsd,
            ];

//

        }

        foreach ($allData as $key2 => $data) {
            if ($allData[0] != $data && $allData[$key2]['hour'] == $allData[$key2 - 1]['hour'] && $data['sub_account_id'] == $subAccount->id) {
                $newData = [
                    'date' => $data['date'],
                    'hour' => $data['hour'],
                    'master_account_id' => $masterAccount->id,
                    'sub_account_id' => $subAccount->id,
                    'cost' => $data['cost'] + $allData[$key2 - 1]['cost'],
                    'cost_usd' => $data['cost_usd'] + $allData[$key2 - 1]['cost_usd'],
                ];
                array_pop($formattedData);
                $formattedData[] = $newData;
            } else {
                $newData = [
                    'date' => $data['date'],
                    'hour' => $data['hour'],
                    'master_account_id' => $masterAccount->id,
                    'sub_account_id' => $subAccount->id,
                    'cost' => $data['cost'],
                    'cost_usd' => $data['cost_usd'],
                ];
                $formattedData[] = $newData;
            }
        }

        $this->storeHourlyData($formattedData);
        return $formattedData;
    }


    /**
     * @return mixed|void
     */
    public function getUsdRate()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://exchangerate-api.p.rapidapi.com/rapid/latest/USD",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "X-RapidAPI-Host: exchangerate-api.p.rapidapi.com",
                "X-RapidAPI-Key: " . config('googleAds.rapid_api_key')
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            return $response['rates']['ARS'];
        }
    }

    /**
     * Details of Sub Accounts of Provided Master Account.
     *
     * @param $masterAccount
     * @param GoogleAdsClient $googleAdsClient
     * @return array|Application|ResponseFactory|Response
     * @throws ApiException
     * @throws ValidationException
     */
    public function getAccountTree($masterAccount, GoogleAdsClient $googleAdsClient)
    {
        $managerCustomerId = $masterAccount->account_id;
        $loginCustomerId = $masterAccount->account_id;
        $rootCustomerIds = [];
        if (is_null($managerCustomerId)) {
            // We will get the account hierarchies for all accessible customers.
            $rootCustomerIds = self::getAccessibleCustomers($googleAdsClient);
        } else {
            // We will get only the hierarchy for the provided manager customer ID when it's
            // provided.
            $rootCustomerIds[] = $managerCustomerId;
        }

        $allHierarchies = [];
        $accountsWithNoInfo = [];

        // Constructs a map of account hierarchies.
        foreach ($rootCustomerIds as $rootCustomerId) {
            $customerClientToHierarchy = self::createCustomerClientToHierarchy($loginCustomerId, $rootCustomerId, $googleAdsClient);
            if (is_null($customerClientToHierarchy)) {
                $accountsWithNoInfo[] = $rootCustomerId;
            } else {
                $allHierarchies += $customerClientToHierarchy;
            }
        }

        // Prints the IDs of any accounts that did not produce hierarchy information.
        if (!empty($accountsWithNoInfo)) {
            $accounts = [];
            foreach ($accountsWithNoInfo as $accountId) {
                $accounts [] = $accountId;
            }
            return response(['success' => false, 'message' => 'Unable to retrieve information for the following accounts which are likely either test accounts or accounts with setup issues. Please check the logs for details:', 'accounts' => $accounts]);
        }

        $accountTree = [];
        foreach ($allHierarchies as $rootCustomerId => $customerIdsToChildAccounts) {
            $this->printAccountHierarchy(
                self::$rootCustomerClients[$rootCustomerId],
                $customerIdsToChildAccounts,
                0
            );
            $accountTree [] = $this->getHierarchy();
        }
        return array_merge(...$accountTree);
    }

    /**
     * Creates a map between a customer client and each of its managers' mappings.
     *
     * @param int|null $loginCustomerId the login customer ID used to create the GoogleAdsClient
     * @param int $rootCustomerId the ID of the customer at the root of the tree
     * @return array|null a map between a customer client and each of its managers' mappings if the
     *     account hierarchy can be retrieved. If the account hierarchy cannot be retrieved, returns
     *     null
     * @throws ApiException
     */
    private function createCustomerClientToHierarchy(
        ?int $loginCustomerId,
        int  $rootCustomerId,
             $googleAdsClient
    ): ?array
    {
        // Creates the Google Ads Service client.
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all child accounts of the manager specified in search calls below.
        $query = 'SELECT customer_client.client_customer, customer_client.level,'
            . ' customer_client.manager, customer_client.descriptive_name,'
            . ' customer_client.currency_code, customer_client.time_zone,'
            . ' customer_client.id FROM customer_client WHERE customer_client.level <= 1';

        $rootCustomerClient = null;
        // Adds the root customer ID to the list of IDs to be processed.
        $managerCustomerIdsToSearch = [$rootCustomerId];

        // Performs a breadth-first search algorithm to build an associative array mapping
        // managers to their child accounts ($customerIdsToChildAccounts).
        $customerIdsToChildAccounts = [];

        while (!empty($managerCustomerIdsToSearch)) {
            $customerIdToSearch = array_shift($managerCustomerIdsToSearch);
            // Issues a search request by specifying page size.
            /** @var GoogleAdsServerStreamDecorator $stream */
            $stream = $googleAdsServiceClient->searchStream(
                $customerIdToSearch,
                $query
            );

            // Iterates over all elements to get all customer clients under the specified customer's
            // hierarchy.
            foreach ($stream->iterateAllElements() as $googleAdsRow) {
                /** @var GoogleAdsRow $googleAdsRow */
                $customerClient = $googleAdsRow->getCustomerClient();

                // Gets the CustomerClient object for the root customer in the tree.
                if ($customerClient->getId() === $rootCustomerId) {
                    $rootCustomerClient = $customerClient;
                    self::$rootCustomerClients[$rootCustomerId] = $rootCustomerClient;
                }

                // The steps below map parent and children accounts. Continue here so that managers
                // accounts exclude themselves from the list of their children accounts.
                if ($customerClient->getId() === $customerIdToSearch) {
                    continue;
                }

                // For all level-1 (direct child) accounts that are a manager account, the above
                // query will be run against them to create an associative array of managers to
                // their child accounts for printing the hierarchy afterwards.
                $customerIdsToChildAccounts[$customerIdToSearch][] = $customerClient;
                // Checks if the child account is a manager itself so that it can later be processed
                // and added to the map if it hasn't been already.
                if ($customerClient->getManager()) {
                    // A customer can be managed by multiple managers, so to prevent visiting
                    // the same customer multiple times, we need to check if it's already in the
                    // map.
                    $alreadyVisited = array_key_exists(
                        $customerClient->getId(),
                        $customerIdsToChildAccounts
                    );
                    if (!$alreadyVisited && $customerClient->getLevel() === 1) {
                        $managerCustomerIdsToSearch[] = $customerClient->getId();
                    }
                }
            }
        }

        return is_null($rootCustomerClient) ? null
            : [$rootCustomerClient->getId() => $customerIdsToChildAccounts];
    }

    /**
     * Retrieves a list of accessible customers with the provided set up credentials.
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @return int[] the list of customer IDs
     * @throws ApiException
     * @throws ValidationException
     */
    private function getAccessibleCustomers(GoogleAdsClient $googleAdsClient): array
    {
        $accessibleCustomerIds = [];
        // Issues a request for listing all customers accessible by this authenticated Google account.
        $customerServiceClient = $googleAdsClient->getCustomerServiceClient();
        $accessibleCustomers = $customerServiceClient->listAccessibleCustomers();

        print 'No manager customer ID is specified. The example will print the hierarchies of'
            . ' all accessible customer IDs:' . PHP_EOL;
        foreach ($accessibleCustomers->getResourceNames() as $customerResourceName) {
            $customer = CustomerServiceClient::parseName($customerResourceName)['customer_id'];
            print $customer . PHP_EOL;
            $accessibleCustomerIds[] = intval($customer);
        }
        print PHP_EOL;

        return $accessibleCustomerIds;
    }

    /**
     * Returns the specified account's hierarchy using recursion.
     *
     * @param CustomerClient $customerClient the customer client whose info will be printed and
     *     its child accounts will be processed if it's a manager
     * @param array $customerIdsToChildAccounts a map from customer IDs to child
     *     accounts
     * @param int $depth the current depth we are printing from in the
     *     account hierarchy
     */
    private function printAccountHierarchy(
        CustomerClient $customerClient,
        array          $customerIdsToChildAccounts,
        int            $depth
    )
    {
        $customerId = $customerClient->getId();
        $this->setHierarchy(json_decode($customerClient->serializeToJsonString(), true));
        // Recursively call this function for all child accounts of $customerClient.
        if (array_key_exists($customerId, $customerIdsToChildAccounts)) {
            foreach ($customerIdsToChildAccounts[$customerId] as $childAccount) {
                self::printAccountHierarchy($childAccount, $customerIdsToChildAccounts, $depth + 1);
            }
        }
    }

    private function setHierarchy($data)
    {
        $this->hierarchy [] = $data;
    }

    private function getHierarchy()
    {
        return $this->hierarchy;
    }


}