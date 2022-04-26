<?php
namespace App\Admin\Models;

use Encore\Admin\Auth\Database\Administrator as BaseAdministrator;
use GuzzleHttp\Client;
use Auth;

class Administrator extends  BaseAdministrator{

    protected $client;
    protected $authentication = null;

	public function __construct( array $attributes = [] ) {

		parent::__construct( $attributes );

        array_push( $this->fillable, 'partner_fee', 'excluded_channels', 'api_password', 'adtelligent_account_id', 'api_token' );

    }

    public function getExcludedChannelsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setExcludedChannelsAttribute($value)
    {
        $this->attributes['excluded_channels'] = implode(',', $value);
    }


    public static function APIAuth( $username, $password) {

        $authEndpoint = env('ADTELLIGENT_AUTH_BASE_URL');

        $client = new Client([
            'base_uri' => $authEndpoint
        ]);

        try {
            $request = $client->request( 'POST', '/api/v1/auth', ['json' => [ 'username' => $username, 'password' => $password ]] );
        } catch (\Throwable $th) {
            return false;
            //throw $th;
        }

        if($request->getStatusCode() != 200)
            return false;

        $response = \json_decode($request->getBody()->getContents(), true);

        if(!$response['success']) return false;

        return $response;
    }

    public function APIAuthenticate() {

        $response = self::APIAuth($this->username, $this->api_password);

        if(!$response) return false;

        if(empty($this->adtelligent_account_id))
            $this->adtelligent_account_id = $response['data']['sessionData']['account_id'];

        $this->api_token =  $response['data']['sessionData']['sid'];

        $this->save();


        return $response['success'];
    }

    public function request( $path, $params ) {

        $api_base = env('ADTELLIGENT_BASE_URL');

        $client = new Client([
            'base_uri' => $api_base
        ]);

        $request = $client->request( 'GET', '/api/statistics/ssp2/' . $path, [
            'query' => $params,
            'headers' => [
                'x-authentication-session-id' => $this->api_token,
                'Accept'     => 'application/json'
            ]
        ] );

        if($request->getStatusCode() != 200)
            return false;

        $response = \json_decode($request->getBody()->getContents(), true);

        return $response;
    }

    public function seats(){
        return $this->belongsToMany(Administrator::class,'admin_seats','admin_user_id','seat_id','id','id');
    }

    public function users(){
        return $this->belongsToMany(Administrator::class,'admin_seats','seat_id','admin_user_id','id','id');
    }
}
