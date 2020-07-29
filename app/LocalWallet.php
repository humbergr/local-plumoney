<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;

class LocalWallet extends Model
{
    public static function walletBalance()
    {
        $client   = new Client();
        $endpoint = '/api/wallet-balance/';

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
//      $mt = microtime(true);
//      $mt = str_replace('.','',$mt);
//      $nonce = $mt;
        $mt    = explode(' ', microtime());
        $nonce = $mt[1] . substr($mt[0], 2, 6);

        //creating signature
        $message       = $nonce . $key . $endpoint;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        $res  = $client->request('GET', 'https://localbitcoins.com' . $endpoint, [
            'headers' => [
                'Apiauth-Key'       => $key,
                'Apiauth-Nonce'     => $nonce,
                'Apiauth-Signature' => $signature
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        return $data['data']['total']['balance'];
    }
}
