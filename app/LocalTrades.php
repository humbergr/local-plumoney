<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use App\Credential;
use Carbon\Carbon;
use App\Transaction;

class LocalTrades extends Model
{
    public static function getDashboard()
    {
        $client   = new Client();
        $endpoint = '/api/ads/';

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

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

        return $data;
    }

    public static function getClosedTrades()
    {
        $client   = new Client();
        $endpoint = '/api/dashboard/closed/';

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

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

        return $data;
    }

    public static function getCompletedTrades()
    {
        $client   = new Client();
        $endpoint = '/api/dashboard/released/';

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

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

        return $data;
    }

    public static function getCancelledTrades()
    {
        $client   = new Client();
        $endpoint = '/api/dashboard/canceled/';

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

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

        return $data;
    }

    public static function tradesPage($url)
    {
        $client = new Client();

        $parts    = parse_url($url);
        $params   = $parts['query'];
        $endpoint = $parts['path'];

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

        //creating signature
        $message       = $nonce . $key . $endpoint . $params;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        $res  = $client->request('GET', $url, [
            'headers' => [
                'Apiauth-Key'       => $key,
                'Apiauth-Nonce'     => $nonce,
                'Apiauth-Signature' => $signature
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        return $data;
    }

    public static function getTransactiosList($date)
    {
        $client   = new Client();
        $endpoint = '/api/dashboard/released/';

        //getting credential
        $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

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

        $cond = true;

        $transactions = $data['data']['contact_list'];

        $last_date       = new Carbon($date);
        $last_array_date = new Carbon($transactions[49]['data']['released_at']);

        if ($last_date >= $last_array_date) {
            $cond = false;
        }

        while ($cond) {
            $client   = new Client();
            $endpoint = '/api/dashboard/released/';

            //getting credential
            $credential = Credential::where('is_active', 1)->first();

            $parts  = parse_url($data['pagination']['next']);
            $params = $parts['query'];

            //setting auth-tokens
            $key         = env('LOCAL_HMAC_KEY_' . $credential->env_number);
            $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential->env_number);

            //creating nonce
            $mt    = microtime(true);
            $mt    = str_replace('.', '', $mt);
            $nonce = $mt;

            //creating signature
            $message       = $nonce . $key . $endpoint . $params;
            $message_bytes = utf8_encode($message);
            $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

            // api request
            $res  = $client->request('GET', $data['pagination']['next'], [
                'headers' => [
                    'Apiauth-Key'       => $key,
                    'Apiauth-Nonce'     => $nonce,
                    'Apiauth-Signature' => $signature
                ]
            ]);
            $data = json_decode($res->getBody(), true);

            $transactions = array_merge($transactions, $data['data']['contact_list']);

            $last_array_date = new Carbon($data['data']['contact_list'][49]['data']['released_at']);

            if ($last_date > $last_array_date) {
                $cond = false;
            }
        }

        return $transactions;
    }

    public static function getTransactionListV2($date = '')
    {
        //array de numero de credencial
        $env_numbers = [];
        $credentials = Credential::all();
        foreach ($credentials as $cred) {
            $env_numbers[] = [
                'env_number' => $cred->env_number,
                'url'        => 'https://localbitcoins.com/api/dashboard/released/',
                'params'     => []
            ];
        }

        $trans_to_return = [];
        $next            = '';
        //haciendo el primer request para cada numero de credencial
        foreach ($env_numbers as $key => $credential) {
            $trans                       = LocalTrades::getTransactions($credential['env_number'],
                '/api/dashboard/released/');
            $trans_to_return             = array_merge($trans_to_return, $trans[0]);
            $env_numbers[$key]['url']    = $trans[1];
            $next                        = $trans['1'];
            $parts                       = parse_url($next);
            $env_numbers[$key]['params'] = $parts['query'];
        }

        $parts  = parse_url($next);
        $params = $parts['query'];
        //ordenando por released_date
        usort($trans_to_return, array('App\LocalTrades', 'sortFunction'));
        //creando condicional para el while
        $cond = true;

        $last_date       = new Carbon($date);
        $last_array_date = new Carbon($trans_to_return[0]['data']['released_at']);

        if ($last_date >= $last_array_date) {
            $cond = false;
        }
        //while
        while ($cond) {
            foreach ($env_numbers as $key => $credential) {
                $trans                       = LocalTrades::getTransactions2($credential['env_number'],
                    $credential['url'], $credential['params']);
                $trans_to_return             = array_merge($trans_to_return, $trans[0]);
                $env_numbers[$key]['url']    = $trans[1];
                $next                        = $trans['1'];
                $parts                       = parse_url($next);
                $env_numbers[$key]['params'] = $parts['query'];
            }

            usort($trans_to_return, array('App\LocalTrades', 'sortFunction'));

            $last_array_date = new Carbon($trans_to_return[0]['data']['released_at']);

            if ($last_date > $last_array_date) {
                $cond = false;
            }
        }

        return $trans_to_return;
    }

    public static function sortFunction($a, $b)
    {
        return strtotime($a['data']['released_at']) - strtotime($b['data']['released_at']);
    }

    private static function getTransactions($credential, $endpoint = '/api/dashboard/released/', $params = '')
    {
        $client = new Client();

        //getting credential
        //  $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

        //creating signature
        $message       = $nonce . $key . $endpoint . $params;
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

        $cond = true;
        //  return dd($data);
        $transactions = $data['data']['contact_list'];

        return [$transactions, $data['pagination']['next']];
    }

    private static function getTransactions2($credential, $next_url, $params = '')
    {
        $client   = new Client();
        $endpoint = '/api/dashboard/released/';

        //getting credential
        //  $credential = Credential::where('is_active', 1)->first();

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $credential);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $credential);

        //creating nonce
        $mt              = explode(' ', microtime());
        $nonce           = $mt[1] . substr($mt[0], 2, 6);

        //creating signature
        $message       = $nonce . $key . $endpoint . $params;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        $res  = $client->request('GET', $next_url, [
            'headers' => [
                'Apiauth-Key'       => $key,
                'Apiauth-Nonce'     => $nonce,
                'Apiauth-Signature' => $signature
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        $cond = true;
        //  return dd($data);
        $transactions = $data['data']['contact_list'];

        return [$transactions, $data['pagination']['next']];
    }
}
