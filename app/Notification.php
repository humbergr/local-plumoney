<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;

class Notification extends Model
{
    protected $fillable = ['msg', 'notification_id', 'contact_id', 'advertisement_id'];

    public static function setLastNotification()
    {
      $client = new Client();
      $endpoint = '/api/notifications/';

      //getting credential
      $credential = Credential::where('is_active', 1)->first();

      //setting auth-tokens
      $key = env('LOCAL_HMAC_KEY_'.$credential->env_number);
      $hmac_secret = env('LOCAL_HMAC_SECRET_'.$credential->env_number);

      //creating nonce
      $mt = microtime(true);
      $mt = str_replace('.','',$mt);
      $nonce = $mt;

      //creating signature
      $message = $nonce . $key . $endpoint;
      $message_bytes = utf8_encode($message);
      $signature = mb_strtoupper( hash_hmac( 'sha256', $message_bytes, $hmac_secret ) );

      // api request
      $res = $client->request('GET', 'https://localbitcoins.com'.$endpoint, [
        'headers' => [
          'Apiauth-Key' => $key,
          'Apiauth-Nonce' => $nonce,
          'Apiauth-Signature' => $signature
        ]
      ]);

      $data = json_decode($res->getBody(), true);

      Notification::create(['msg' => $data['data'][0]['msg'], 'notification_id' => $data['data'][0]['id'], 'contact_id' => $data['data'][0]['contact_id']]);

      return 'success';
    }
}
