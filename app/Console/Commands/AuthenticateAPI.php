<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Admin\Models\Administrator;

class AuthenticateAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:apis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Authenticate API users and store access token';

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
        //
        $seats = Administrator::where('api_password', '!=', null)->get();
        foreach($seats as $seat) {
            $seat->APIAuthenticate();
        }
        
    }
}
