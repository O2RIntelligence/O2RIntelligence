<?php

namespace App\Services\GoogleAds;

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
     * Gets Total Cost of a sub account
     *
     * @param GoogleAdsClient $googleAdsClient the Google Ads API client
     * @param $subAccount
     * @param $dateRange
     * @return array
     * @throws ApiException
     */
    public static function getTotalCost(GoogleAdsClient $googleAdsClient, $subAccount, $dateRange)
    {
        $customerId = $subAccount->account_id;
        $googleAdsServiceClient = $googleAdsClient->getGoogleAdsServiceClient();
        // Creates a query that retrieves all keyword statistics.
        $query = "SELECT metrics.cost_micros, segments.date FROM campaign WHERE segments.date BETWEEN '" . $dateRange['startDate'] . "' AND '" .  $dateRange['endDate']  . "' AND customer.id = ".$customerId;//." AND metrics.cost_micros > 0";
        // Issues a search stream request.
        /** @var GoogleAdsServerStreamDecorator $stream */
        $stream = $googleAdsServiceClient->searchStream($customerId, $query);
        // Iterates over all rows in all messages and prints the requested field values for the keyword in each row.
        $results = [];
        $totalCost = 0;
        foreach ($stream->iterateAllElements() as $key => $googleAdsRow) {
            $results[] = json_decode($googleAdsRow->serializeToJsonString(), true);
            $totalCost += $results[$key]['metrics']['costMicros'];
            /** @var GoogleAdsRow $googleAdsRow */
        }
        return array('subAccountId' => $customerId ,'subAccountName' => $subAccount->name , 'totalCost' => $totalCost/1000000, 'data'=> $results );
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
//        $googleAdsClient = $this->getGoogleAdsClient()->getGoogleAdsService($masterAccount);
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
    private function setHierarchy($data){
        $this->hierarchy [] = $data;
    }
    private function getHierarchy(){
        return $this->hierarchy;
    }


}