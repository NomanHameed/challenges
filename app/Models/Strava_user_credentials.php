<?php

namespace App\Models;

use CodeToad\Strava\Strava;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Strava_user_credentials extends Model
{
    public static $http_client = null;

    public function httpClient(): ?Strava
    {
        if(!self::$http_client) {

            self::$http_client = new Strava(
                config('ct_strava.client_id'),
                config('ct_strava.client_secret'),
                config('ct_strava.redirect_uri'),
                new Client()
            );
        }
        return self::$http_client;


    }

}
