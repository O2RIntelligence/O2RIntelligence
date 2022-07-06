<?php

namespace App\Console\Commands;

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
        if ((new \App\Http\Controllers\GoogleAds\DailyDataController)->getDailyDataFromService()) {
            $this->info('Daily Data Updated Successfully');
        }else $this->info('Daily Data Update Failed');
    }
}
