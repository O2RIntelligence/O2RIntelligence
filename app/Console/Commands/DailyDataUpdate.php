<?php

namespace App\Console\Commands;

use App\Http\Controllers\GoogleAds\DailyDataController;
use App\Http\Controllers\GoogleAds\SubAccountController;
use App\Services\GoogleAds\GoogleAdsService;
use Illuminate\Console\Command;

class DailyDataUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyData:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Google Ads Daily Data for the Current Day All Master Accounts and Sub Accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ((new DailyDataController)->getDailyDataFromService()) {
            $this->info('Daily Data Updated Successfully');
        }else $this->info('Daily Data Update Failed');

        if ((new GoogleAdsService)->getUsdRateFromRapidAPI()) {
            $this->info('Exchange Rate Updated Successfully');
        }else $this->info('Exchange Rate Update Failed');

        $monthlyDataUpdate = (new DailyDataController)->getMonthlyData();
        $this->info($monthlyDataUpdate);

        if ($response = (new SubAccountController)->getSubAccountsFromGoogleAds()) {
            $ids = "";
            if($response['success'] && !empty($response['actions_taken'])) {
                foreach ($response['actions_taken'] as $id){
                    $ids = $ids." ".$id;
                }
                $this->info('Sub Account DISABLE detected from Google Ads Service, Deleted Accounts: '.$ids);
            }
            elseif($response['success'] && empty($response['actions_taken'])) $this->info('All Sub Account Status OK');
            elseif(!$response['success']) $this->info('Sub Account Status Checking Failed');
        }else $this->info('Sub Account Status Checking Failed');



    }
}
