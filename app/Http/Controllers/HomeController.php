<?php

namespace App\Http\Controllers;

use App\Akb\Banker;
use App\Akb\Toolkit;
use App\DestinationAccount;
use App\OutgoingBtc;
use App\UserExchangeTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\BtcBssData;
use App\UsdBssData;
use GuzzleHttp\Client;
use App\BitstampData;
use App\CurrencyWallet;
use App\Country;
use Pusher\Laravel\Facades\Pusher;
use URL;
use App\MarketData;
use App\LocalWallet;
use App\LocalTrades;
use App\Credential;
use App\User;
use App\Tier;

use Carbon\Carbon;

use App\Mail\ForgotPasswordEmail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Credential $credential)
    {
        $this->credential = Credential::where('id', 1)->first();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHome()
    {

        
        if (Auth::user() === null) {
            return view('home-akbfintech');
        }

        $orders = UserExchangeTransactions::where('user_id', Auth::user()->id)
            ->where([
                'approved_at' => null,
                'failed_at'   => null
            ])
            ->get();

        return view('home-akbfintech')->with(compact('orders'));

    }

    public function getClientHome()
    {
        $userWallets = CurrencyWallet::where([
            'user_id'  => Auth::user()->id,
            'status'   => 1,
            'currency' => 'USD'
        ])->first();

        if (empty($userWallets)) {
            $banker = new Banker;
            $userWallets = $banker->createWallet('USD', null, true)->toArray();
        }

        $transactions = array_slice($userWallets['relatedTransactions'], 0, 3);

        $destinations = DestinationAccount::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->paginate(3);
        
        $tier = Tier::where('user_id', Auth::user()->id)->where('in_review', 1)->with('tierLevel')->first();
        
        return view('client-home')->with(compact('userWallets', 'transactions', 'destinations', 'tier'));
    }

    public function forgotPassword()
    {
        return view('forgot-password');
    }

    public function postForgotPassword()
    {
        $inputs = request()->all();

        $user = User::where('email', $inputs['email'])->first();

        if (is_null($user)) {
            return Redirect::to('forgot-password')->with('error', 'No existe un usuario con esa direccion de email.');
        }

        $user->verification_token = str_random(24);
        $user->save();

        $data = [
            'name' => $user->name,
            'url'  => URL::to('/forgotten-password-form' . '?token=' . $user->verification_token),
        ];

        Mail::to($inputs['email'])->send(new ForgotPasswordEmail($data));

        return Redirect::back()->with('success', 'Se ha enviado un correo de recuperación de contraseña.');
    }

    public function index()
    {
        if (Auth::user()->role_id == 3) {
            return Redirect::to('/create-transaction');
        }
        $countries  = Country::all();
        $marketData = new MarketData;
        $data       = $marketData::getAdvertisements('https://localbitcoins.com/sell-bitcoins-online/VE/venezuela/.json');
        $baseurl    = 'https://localbitcoins.com/sell-bitcoins-online/VE/venezuela/.json';
        //$data = $marketData::indexData();

        //wallet balance
        $balance = LocalWallet::walletBalance();

        $ves = CurrencyWallet::where('currency', 'VES')->first();
        $usd = CurrencyWallet::where('currency', 'USD')->first();

        return view('dashboard.index')->with(compact('data', 'countries', 'baseurl', 'balance', 'ves', 'usd'));
    }

    public function indexV2()
    {
        $countries = Country::countryArray();
        $traders   = User::where('role_id', '4')->get();

        $exchanges = UserExchangeTransactions::with('destinationAccount')
            ->where([
                'trader_id' => Auth::user()->id,
                'status'    => 0
            ])
            ->whereNull('contact_id')
            ->get();

        return view('coinbank.app')->with(compact('countries', 'traders', 'exchanges'));
    }

    public function dashboard()
    {
        //wallet balance
        //$balance = LocalWallet::walletBalance();
        //advertisements
        $ads = LocalTrades::getDashboard();
        //return dd($ads);
        $methods = MarketData::paymentMethods();

        return view('dashboard.dashboard')->with(compact('ads', 'methods'));
    }

    public function getSearchPage(){
      $inputs = request()->all();

      $traders = User::where('role_id', 4)->get();
      $bitstamp = BitstampData::getNow();

      if (!isset($inputs['country'])) {
        $inputs['country'] = 'VE';
        $inputs['operation'] = 'sell';
      }

      $countries = Country::all();
      $country = Country::where('code', $inputs['country'])->first();
      $url = 'https://localbitcoins.com/'.$inputs['operation'].'-bitcoins-online/'.$country->code.'/'.$country->name.'/.json';
      $client = new Client();
      $res = $client->request('GET', $url);
      $data = json_decode($res->getBody(), true);
      //filtering $ads
      $filtered = [];
      if (isset($inputs['bank']) && isset($inputs['amount'])) {
        foreach ($data['data']['ad_list'] as $ad) {
          if (preg_match($inputs['bank'], $ad['data']['bank_name']) && $inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
            array_push($filtered, $ad);
          }
        }

        $countries = Country::all();
        $country   = Country::where('code', $inputs['country'])->first();
        $url       = 'https://localbitcoins.com/' . $inputs['operation'] . '-bitcoins-online/' . $country->code . '/' . $country->name . '/.json';
        $client    = new Client();
        $res       = $client->request('GET', $url);
        $data      = json_decode($res->getBody(), true);
        //filtering $ads
        $filtered = [];
        if (isset($inputs['bank']) && isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if (preg_match($inputs['bank'],
                        $ad['data']['bank_name']) && $inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
                    array_push($filtered, $ad);
                }
            }
        } elseif (!isset($inputs['bank']) && isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if ($inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
                    array_push($filtered, $ad);
                }
            }
        } elseif (isset($inputs['bank']) && !isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if (preg_match($inputs['bank'], $ad['data']['bank_name'])) {
                    array_push($filtered, $ad);
                }
            }
        } else {
            $filtered = $data['data']['ad_list'];
        }

        if (isset($data['pagination']['next'])) {
            $ads_data = ['next_page' => $data['pagination']['next'], 'ads' => $filtered];
        } else {
            $ads_data = ['next_page' => '', 'ads' => $filtered];
        }
      } else {
        $filtered = $data['data']['ad_list'];
      }

      if (isset($data['pagination']['next'])) {
        $ads_data = ['next_page' => $data['pagination']['next'], 'ads' => $filtered];
      } else {
        $ads_data = ['next_page' => '', 'ads' => $filtered];
      }

      (isset($inputs['bank'])) ? $bank = $inputs['bank'] : $bank = '';
      (isset($inputs['amount'])) ? $amount = $inputs['amount'] : $amount = '';
      ($inputs['operation'] == 'sell') ? $active = false : $active = true;
      $operation = $inputs['operation'];
      //return dd(['ads_data' => $ads_data, 'countries' => $countries, 'bank' => $bank, 'amount' => $amount, 'operation' => $operation, 'active' => $active]);
      return view('ads-filter')->with(compact('ads_data', 'countries', 'bank', 'amount', 'operation', 'active', 'bitstamp', 'traders'));
    }


    public function getAdvertisements()
    {
        $url        = request()->input('url');
        $marketData = new MarketData;
        $data       = $marketData::getAdvertisements($url);

        return $data;
    }

    public function marketDataNow()
    {
        $client = new Client();
        $res    = $client->request('GET', 'https://localbitcoins.com/buy-bitcoins-online/VES/.json');
        $data   = json_decode($res->getBody(), true);

        return dd($data);
        $bsBitcoin = $i = 0;
        foreach ($data['data']['ad_list'] as $ad) {
            $i++;
            $bsBitcoin = $bsBitcoin + $ad['data']['temp_price'];
        }
        $bsBitcoin = $bsBitcoin / $i;

        $client2    = new Client();
        $res2       = $client2->request('GET', 'https://localbitcoins.com/buy-bitcoins-online/US/united_states/.json');
        $data2      = json_decode($res2->getBody(), true);
        $usdBitcoin = $j = 0;
        foreach ($data2['data']['ad_list'] as $ad) {
            $j++;
            $usdBitcoin = $usdBitcoin + $ad['data']['temp_price'];
        }
        $usdBitcoin = $usdBitcoin / $j;

        return response()->json(['bsNow' => round($bsBitcoin, 2), 'usdNow' => round($usdBitcoin, 2)]);
    }

    public function marketData()
    {
        $marketData = new MarketData;
        $data       = $marketData::trickerAll();
        $resp       = ['ticker' => $data, 'bsd' => BitstampData::getData()];

        return response()->json($resp);
    }

    public function getBitstampData()
    {
        $client   = new Client();
        $res      = $client->request('GET', 'https://www.bitstamp.net/api/ticker/');
        $data     = json_decode($res->getBody(), true);
        $monthAgo = Carbon::now('UTC')->subMonths(2)->format('Y-m-d H:i:s');

        BitstampData::where('created_at', '<=', $monthAgo)->delete();
        BitstampData::create(['price' => $data['last'], 'hour' => Carbon::now()->format('H')]);

        return 'success';
    }

    public function getVolumeT($code)
    {
        // $country    = Country::where('code', $code)->first();
        $marketData = new MarketData;
        $data       = $marketData::getVolumeT($code);

        return $data;
    }

    public function getVolumeB($code)
    {
        $country    = Country::where('code', $code)->first();
        $marketData = new MarketData;
        $data       = $marketData::getVolumeB($code, $country->name);

        return $data;
    }
    
    public function getVolumeS($code)
    {
        $country    = Country::where('code', $code)->first();
        $marketData = new MarketData;
        $data       = $marketData::getVolumeS($code, $country->name);

        return $data;
    }

    public function getAds($code)
    {
        $inputs  = request()->all();
        $country = Country::where('code', $code)->first();
        $url     = 'https://localbitcoins.com/' . $inputs['operation'] . '-bitcoins-online/' . $code . '/' . $country->name . '/.json';
        $client  = new Client();
        $res     = $client->request('GET', $url);
        $data    = json_decode($res->getBody(), true);
        //filtering $ads
        $filtered = [];
        if (isset($inputs['bank']) && isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if (preg_match($inputs['bank'],
                        $ad['data']['bank_name']) && $inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
                    array_push($filtered, $ad);
                }
            }
        } elseif (!isset($inputs['bank']) && isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if ($inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
                    array_push($filtered, $ad);
                }
            }
        } elseif (isset($inputs['bank']) && !isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if (preg_match($inputs['bank'], $ad['data']['bank_name'])) {
                    array_push($filtered, $ad);
                }
            }
        } else {
            $filtered = $data['data']['ad_list'];
        }

        if (isset($data['pagination']['next'])) {
            return ['next_page' => $data['pagination']['next'], 'ads' => $filtered];
        } else {
            return ['next_page' => '', 'ads' => $filtered];
        }

    }

    public function getNext()
    {
        $inputs = request()->all();

        try {

            $client = new Client();
            $res    = $client->request('GET', $inputs['url']);
            $data   = json_decode($res->getBody(), true);

        } catch (\Exception $e) {
            return ['error' => 'Localbitcoins server error.'];
        }

        //filtering $ads
        $filtered = [];
        if (isset($inputs['bank']) && isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if (preg_match($inputs['bank'],
                        $ad['data']['bank_name']) && $inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
                    array_push($filtered, $ad);
                }
            }
        } elseif (!isset($inputs['bank']) && isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if ($inputs['amount'] > $ad['data']['min_amount'] && $inputs['amount'] < $ad['data']['max_amount']) {
                    array_push($filtered, $ad);
                }
            }
        } elseif (isset($inputs['bank']) && !isset($inputs['amount'])) {
            foreach ($data['data']['ad_list'] as $ad) {
                if (preg_match($inputs['bank'], $ad['data']['bank_name'])) {
                    array_push($filtered, $ad);
                }
            }
        } else {
            $filtered = $data['data']['ad_list'];
        }

        if (isset($data['pagination']['next'])) {
            return ['next_page' => $data['pagination']['next'], 'ads' => $filtered];
        } else {
            return ['next_page' => '', 'ads' => $filtered];
        }

    }

    public function getHelp()
    {
        return view('help');
    }

    public function getAgreement()
    {
        return view('privacy.agreement');
    }

    public function getPolicies()
    {
        return view('privacy.privacy-policies');
    }

    public function getCookies()
    {
        return view('privacy.cookies-policies');
    }

    public function getLicenses()
    {
        return view('privacy.licenses');
    }

    public function getWagreement()
    {
        return view('privacy.wallets-agreement');
    }

    /**
     * @param string|array $ids
     *
     * @return JsonResponse
     */
    public function kickuser(string $ids): JsonResponse
    {
        $ids = explode(',', $ids);
        Toolkit::kickUsers($ids);

        return response()->json(['message' => 'success']);
    }

}
