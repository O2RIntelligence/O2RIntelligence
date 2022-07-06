<?php

namespace App\Console\Commands;

use App\Http\Controllers\GoogleAds\HourlyDataController;
use App\Services\GoogleAds\GoogleAdsService;
use Illuminate\Console\Command;

class HourlyDataUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hourlyData:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Google Ads Hourly Data for the Current Day All Master Accounts and Sub Accounts';

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
        if ((new \App\Http\Controllers\GoogleAds\HourlyDataController)->getHourlyData()) {
            $this->info('Hourly Data Updated Successfully');
        }else $this->info('Hourly Data Update Failed');
    }
}
