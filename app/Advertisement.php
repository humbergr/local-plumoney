<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Credential;
use GuzzleHttp\Client;

class Advertisement extends Model
{
    protected $fillable = ['ad_id', 'trader_id', 'amount', 'contact_id'];

    public function role()
    {
        return $this->belongsTo('App\User', 'trader_id');
    }

    public static function getById($id){

      $client = new Client();
      $endpoint = '/api/ad-get/'.$id.'/';

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

      return $data;
    }

    public static function openContact($ad_id, $amount)
    {
      $client = new Client();
      $endpoint = '/api/contact_create/'.$ad_id.'/';

      //request data
      $req_data = ['amount' => $amount, 'message' => ''];

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
      $message = $nonce . $key . $endpoint . http_build_query($req_data);
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

      return $data;
    }
}
