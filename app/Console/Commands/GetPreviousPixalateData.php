<?php

namespace App\Console\Commands;

use App\Http\Controllers\PixalateImpressionController;
use Illuminate\Console\Command;

class GetPreviousPixalateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pixalate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Previous Day Pixalate Data From Pixalate Server and save it to DB';

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
        $this->info((new PixalateImpressionController)->getPreviousPixalateData(date('Y-m-d', strtotime('Yesterday'))));
    }
}
