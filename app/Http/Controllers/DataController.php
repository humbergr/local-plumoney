<?php

namespace App\Http\Controllers;

use App\Akb\Banker;
use App\UserWalletsTransactions;
use App\VesUsdPrices;
use App\WebsiteSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

use App\Transaction;
use App\CurrencyWallet;
use App\Notification;
use App\BitstampData;
use App\MarketData;
use App\LocalData;
use App\LocalVolume;
use App\Credential;
use App\Country;
use App\BtcBssData;
use App\UsdBssData;
use GuzzleHttp\Client;
use App\GirosPrice;

class DataController extends Controller
{
    public function __construct(Credential $credential)
    {
        $this->credential = Credential::where('is_active', 1)->first();
    }

    public function getLocalData()
    {
        //API Request
        $marketData = new MarketData;
        $data       = $marketData::trickerAll();

        //Creating Record
        LocalData::create([
            'bs1h'  => $data['VES']['avg_1h'],
            'bs6h'  => $data['VES']['avg_6h'],
            'bs12h' => $data['VES']['avg_12h'],
            'bs24h' => $data['VES']['avg_24h'],
            'us1h'  => $data['USD']['avg_1h'],
            'us6h'  => $data['USD']['avg_6h'],
            'us12h' => $data['USD']['avg_12h'],
            'us24h' => $data['USD']['avg_24h']
        ]);


        return 'success';
    }

    public function getLocalVolume($code)
    {
        //API Request
        $country    = Country::where('code', $code)->first();
        $marketData = new MarketData;
        $data       = $marketData::getVolume($code, $country->name);

        //Creating Record
        LocalVolume::create(['country_code' => $code, 'volume' => $data]);

        return 'success';
    }

    public function marketData()
    {
        $data = LocalData::orderby('id', 'desc')->first();

        return response()->json([
            'ticker'  => $data->arrayData(),
            'bsd'     => BitstampData::getData(),
            'volumes' => LocalVolume::getVolumes()
        ]);
    }

    public function getNextAdvertisements()
    {
        $url        = request()->input('url');
        $marketData = new MarketData;
        $data       = $marketData::getAdvertisements($url);

        return $data;
    }

    public function getAdsCode($operation, $code)
    {
        $country = Country::where('code', $code)->first();
        $url     = 'https://localbitcoins.com/' . $operation . '-bitcoins-online/' . $code . '/' . $country->name . '/.json';
        $client  = new Client();
        $res     = $client->request('GET', $url);
        $data    = json_decode($res->getBody(), true);

        return ['ads' => $data, 'url' => $url];
    }

    public function paymentMethods()
    {
        $client = new Client();
        $res    = $client->request('GET', 'https://localbitcoins.com/api/payment_methods/');
        $data   = json_decode($res->getBody(), true);

        return $data['data']['methods'];
    }

    public function getNotificationsData()
    {
        // $last_not = Notification::orderBy('id', 'DESC')->first();

        $client   = new Client();
        $endpoint = '/api/notifications/';

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $this->credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $this->credential->env_number);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        $res = $client->request('GET', 'https://localbitcoins.com' . $endpoint, [
            'headers' => [
                'Apiauth-Key'       => $key,
                'Apiauth-Nonce'     => $nonce,
                'Apiauth-Signature' => $signature
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        foreach ($data['data'] as $not) {
            /*  if ($last_not->notification_id == $not['id']) {
                break;
              } */

            $notification = Notification::where('notification_id', $not['id'])->first();

            if (strpos($not['url'], '#feedback') !== false && !is_object($notification)) {

                $this->getContactData($not['contact_id']);

                Notification::create([
                    'msg'             => $not['msg'],
                    'notification_id' => $not['id'],
                    'contact_id'      => $not['contact_id']
                ]);

            }
        }

        /*  if ($last_not->notification_id != $data['data'][0]['id']) {
            Notification::create(['msg' => $data['data'][0]['msg'], 'notification_id' => $data['data'][0]['id'], 'contact_id' => $data['data'][0]['contact_id']]);
          } */

        return 'success';
    }

    private function getContactData($contact_id)
    {
        $client   = new Client();
        $endpoint = '/api/contact_info/' . $contact_id . '/';

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $this->credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $this->credential->env_number);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        $res = $client->request('GET', 'https://localbitcoins.com' . $endpoint, [
            'headers' => [
                'Apiauth-Key'       => $key,
                'Apiauth-Nonce'     => $nonce,
                'Apiauth-Signature' => $signature
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        if ($data['data']['buyer']['username'] == env('LOCAL_USERNAME_' . $this->credential->env_number)) {
            $wallet = CurrencyWallet::where('currency', $data['data']['currency'])->first();

            if (isset($wallet)) {
                $wallet->outgoingTransaction($data['data']['amount']);

                $new_transaction = [
                    'bank_name'      => '',
                    'transaction_id' => $contact_id,
                    'amount'         => $data['data']['amount'],
                    'msg'            => 'Bank name must be set by an auditor.',
                    'currency'       => $data['data']['currency'],
                    'type'           => 'Outgoing',
                    'json_data'      => $data
                ];

                if ($data['data']['currency'] == 'VES') {
                    $btc_bss = BtcBssData::orderBy('id', 'desc')->first();
                    $usd_bss = UsdBssData::orderBy('id', 'desc')->first();

                    $new_transaction['usd_price'] = $btc_bss->price / $usd_bss->price;
                }

                Transaction::create($new_transaction);
            }
        }

        return 'success';
    }

    public function getFirstNotification()
    {
        $client   = new Client();
        $endpoint = '/api/notifications/';

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $this->credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $this->credential->env_number);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint;
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        $res = $client->request('GET', 'https://localbitcoins.com' . $endpoint, [
            'headers' => [
                'Apiauth-Key'       => $key,
                'Apiauth-Nonce'     => $nonce,
                'Apiauth-Signature' => $signature
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        Notification::create([
            'msg'             => $data['data'][0]['msg'],
            'notification_id' => $data['data'][0]['id'],
            'contact_id'      => $data['data'][0]['contact_id']
        ]);

        return 'success';
    }

    public function getBitNow()
    {
        return BitstampData::getNow();
    }

    public function getGirosData()
    {
        $client = new Client();

        $res = $client->request('GET', 'https://www.girosapp.com/api/prices/sellprices/', [
            'headers' => [
                'Authorization' => 'Token baed71fff3462aeac88f9a2a621be55bab317fff'
            ]
        ]);

        $data = json_decode($res->getBody(), true);

        foreach ($data as $prices) {
            $client2 = new Client();

            $json_data = $client->request('GET', 'https://www.girosapp.com/api/prices/get/' . $prices['id'], [
                'headers' => [
                    'Authorization' => 'Token baed71fff3462aeac88f9a2a621be55bab317fff'
                ]
            ]);

            GirosPrice::create([
                'price_id'       => $prices['id'],
                'url'            => $prices['url'],
                'currency'       => $prices['currency'],
                'iso'            => $prices['iso'],
                'country'        => $prices['country'],
                'payment_method' => $prices['payment_method'],
                'json_data'      => json_decode($json_data->getBody(), true),
            ]);
        }

        return 'success';
    }

    /**
     * @return array
     */
    public function getPrice(): array
    {
        $inputs = request()->all();

        return Banker::getPrice($inputs);
    }

    /**
     * @param string|null $intention
     *
     * @return array
     */
    public function walletsGetPrice(string $intention = 'charge'): array
    {
        $inputs = request()->all();

        return Banker::walletsGetPrice($inputs, $intention);
    }

    /**
     * @return array
     */
    public function getPriceVes(): array
    {
        $inputs        = request()->all();
        $usdEquivalent = $inputs['amount'] ?? 0;
        $vesPrices     = VesUsdPrices::orderby('id', 'desc')->first();

        if ($inputs['sender'] === 'USD') {
            return [$vesPrices->sell_price, $usdEquivalent, $vesPrices->id];
        }

        $usdEquivalent /= $vesPrices->buy_price;

        return [$vesPrices->buy_price, $usdEquivalent, $vesPrices->id];
    }

    /**
     * Check if the site is open.
     *
     * @return array
     */
    public function checkHours(): array
    {
        $closeHour       = Carbon::createFromTime(17, 0, 0, 'UTC')->getTimestamp();
        $weekCloseHour   = Carbon::createFromTime(22, 0, 0, 'UTC')->getTimestamp();
        $openHour        = Carbon::createFromTime(13, 0, 0, 'UTC')->getTimestamp();
        $currentHour     = Carbon::now('UTC')->getTimestamp();
        $currentDay      = Carbon::now('UTC');
        $currentDay      = $currentDay->dayOfWeek;
        $websiteSettings = WebsiteSettings::find(1);
        $dataReturn      = [
            'close'        => null,
            'close_support_chat' => null
        ];

        if (($currentDay !== 0 && $currentDay !== 6 && $currentDay !== 1 && $websiteSettings['settings']['is_active'] !== 0) ||
            ($currentDay === 6 && $currentHour < $closeHour && $websiteSettings['settings']['is_active'] !== 0) ||
            ($currentDay === 1 && $currentHour > $openHour && $websiteSettings['settings']['is_active'] !== 0)) {
            $dataReturn['close'] = 0;
        } else {
            $dataReturn['close'] = 1;
        }

        if (
            //WeekDays
            (
                $currentDay !== 0 && $currentDay !== 6 && $currentHour > $openHour && $currentHour < $weekCloseHour &&
                $websiteSettings['settings']['is_active'] !== 0
            )
            ||
            //Saturday
            (
                $currentDay === 6 && $currentHour > $openHour && $currentHour < $closeHour &&
                $websiteSettings['settings']['is_active'] !== 0
            )
        ) {
            $dataReturn['close_support_chat'] = 0;
        } else {
            $dataReturn['close_support_chat'] = 1;
        }

        //FastFix
//        if ($currentDay === 0 || $currentDay === 6 || $websiteSettings['settings']['is_active'] === 0) {
//            $dataReturn['close_support_chat'] = 1;
//        }
         if (env('APP_DEBUG')) {
            $dataReturn['close'] = 0;
         }

        return $dataReturn;
    }

    public function testWT()
    {
        $walletsTransactions = UserWalletsTransactions::all()->toArray();
        $byUser              = [];

        foreach ($walletsTransactions as $transaction) {
            $byUser[$transaction['user_id']][] = $transaction;
        }

//        $holdsDown = 0;
//        $holdsUp   = 0;
//        $available = 0;
//
//        foreach ($byUser as $wallet) {
//            $walletData = Banker::getWalletNumbers($wallet, true);
//            $holdsDown  += $walletData['holdsDown'];
//            $holdsUp    += $walletData['holdsUp'];
//            $available  += $walletData['available'];
//        }
//
//        dd(
//            $holdsDown,
//            $holdsUp,
//            $available
//        );

        $availableUsers = [];

        foreach ($byUser as $key => $wallet) {
            $reloads     = [];
            $withdrawals = [];

            foreach ($wallet as $rTransaction) {
                if ($rTransaction['type'] !== 3 && $rTransaction['status'] === 2) {
                    continue;
                }

                if ($rTransaction['type'] === 1 && $rTransaction['status'] !== 3) {
                    $reloads[] = $rTransaction['amount'];
                }

                if ($rTransaction['type'] === 2) {
                    $withdrawals[] = $rTransaction['amount'];
                }
            }

            $userReloads          = array_sum($reloads);
            $userWithdrawals      = array_sum($withdrawals);
            $availableUsers[$key] = $userReloads - $userWithdrawals;
        }

        foreach ($availableUsers as $key => $value) {
            if ($value === 0 || $value === 0.0) {
                unset($availableUsers[$key]);
            }
        }

        dd($availableUsers);
    }
}
