<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;

use GuzzleHttp\Client;

use App\Advertisement;
use App\MarketData;
use App\LocalTrades;
use App\Credential;
use Auth;
use App\ApiHelper;
use App\ContactMessage;
use Carbon\Carbon;

use Pusher\Laravel\Facades\Pusher;

use Pusher\Laravel\Facades\Pusher;

class AdvertisementController extends Controller
{
    public function __construct(Credential $credential)
    {
        $this->credential = Credential::where('is_active', 1)->first();
    }

    public function myAds()
    {
        $ads = LocalTrades::getDashboard();

        return $ads;
    }

    public function getCreateForm()
    {
        $methods = MarketData::paymentMethods();

        return view('advertisements.create')->with(compact('methods'));
    }

    public function postCreate()
    {
        $inputs = request()->all();

        $ad_data = ['trade_type'                    => $inputs['ad-trade_type'],
                    'price_equation'                => $inputs['ad-price_equation'],
                    'lat'                           => $inputs['lat'],
                    'lon'                           => $inputs['lon'],
                    'city'                          => $inputs['city'],
                    'location_string'               => $inputs['location_string'],
                    'countrycode'                   => $inputs['country_code'],
                    'currency'                      => $inputs['currency'],
                    'account_info'                  => '',
                    'bank_name'                     => $inputs['ad-bank_name'],
                    'msg'                           => $inputs['ad-msg'],
                    'sms_verification_required'     => request()->has('ad-sms_verification_required'),
                    'track_max_amount'              => request()->has('ad-track_max_amount'),
                    'require_trusted_by_advertiser' => request()->has('ad-require_trusted_by_advertiser'),
                    'require_identification'        => request()->has('ad-require_identification'),
                    'min_amount'                    => $inputs['ad-min_amount'],
                    'max_amount'                    => $inputs['ad-max_amount'],
                    'limit_to_fiat_amounts'         => $inputs['ad-limit_to_fiat_amounts'],
                    'payment_window_minutes'        => $inputs['ad-payment_window_minutes'],
                    'visible'                       => request()->has('ad-visible'),
                    'online_provider'               => $inputs['ad-online_provider']
        ];

        $client   = new Client();
        $endpoint = '/api/ad-create/';

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $this->credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $this->credential->env_number);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint . http_build_query($ad_data);
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        try {

            $res = $client->request('POST', 'https://localbitcoins.com' . $endpoint, [
                'headers'     => [
                    'Apiauth-Key'       => $key,
                    'Apiauth-Nonce'     => $nonce,
                    'Apiauth-Signature' => $signature
                ],
                'form_params' => $ad_data,
            ]);

        } catch (\Exception $e) {
            return Redirect('/create-advertisement/')->with('error', $e->getMessage());
        }

        $data = json_decode($res->getBody(), true);

        return Redirect('/dashboard')->with('success', $data['data']['message']);
    }

    public function getEditAdvertisement($id)
    {
        $methods           = MarketData::paymentMethods();
        $advertisement     = Advertisement::getById($id)['data']['ad_list'][0]['data'];
        $paymen_currencies = [];

        foreach ($methods as $key => $value) {
            if ($value['code'] == $advertisement['online_provider']) {
                $paymen_currencies = $value['currencies'];
            }
        }

        //return dd($advertisement);
        return view('advertisements.edit')->with(compact('advertisement', 'paymen_currencies'));
    }

    public function postEditAdvertisement($id)
    {
        $inputs = request()->all();

        $ad_data = ['price_equation'                => $inputs['ad-price_equation'],
                    'lat'                           => $inputs['lat'],
                    'lon'                           => $inputs['lon'],
                    'city'                          => $inputs['city'],
                    'location_string'               => $inputs['location_string'],
                    'countrycode'                   => $inputs['country_code'],
                    'currency'                      => $inputs['currency'],
                    'account_info'                  => '',
                    'bank_name'                     => $inputs['ad-bank_name'],
                    'msg'                           => $inputs['ad-msg'],
                    'sms_verification_required'     => request()->has('ad-sms_verification_required'),
                    'track_max_amount'              => request()->has('ad-track_max_amount'),
                    'require_trusted_by_advertiser' => request()->has('ad-require_trusted_by_advertiser'),
                    'require_identification'        => request()->has('ad-require_identification'),
                    'min_amount'                    => $inputs['ad-min_amount'],
                    'max_amount'                    => $inputs['ad-max_amount'],
                    'limit_to_fiat_amounts'         => $inputs['ad-limit_to_fiat_amounts'],
                    'payment_window_minutes'        => $inputs['ad-payment_window_minutes'],
                    'visible'                       => request()->has('ad-visible')
        ];

        $client   = new Client();
        $endpoint = '/api/ad/' . $id . '/';

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $this->credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $this->credential->env_number);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint . http_build_query($ad_data);
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        try {

            $res = $client->request('POST', 'https://localbitcoins.com' . $endpoint, [
                'headers'     => [
                    'Apiauth-Key'       => $key,
                    'Apiauth-Nonce'     => $nonce,
                    'Apiauth-Signature' => $signature
                ],
                'form_params' => $ad_data,
            ]);

        } catch (\Exception $e) {
            return Redirect('/edit-advertisement/' . $id)->with('error', $e->getMessage());
        }

        $data = json_decode($res->getBody(), true);

        return Redirect('/edit-advertisement/' . $id)->with('success', $data['data']['message']);
    }

    public function editEquation($id)
    {
        //return request()->all();
        $inputs        = request()->all();
        $advertisement = Advertisement::getById($id)['data']['ad_list'][0]['data'];

        $ad_data = ['price_equation'                => $inputs['params']['price_equation'],
                    'lat'                           => $advertisement['lat'],
                    'lon'                           => $advertisement['lon'],
                    'city'                          => $advertisement['city'],
                    'location_string'               => $advertisement['location_string'],
                    'countrycode'                   => $advertisement['countrycode'],
                    'currency'                      => $advertisement['currency'],
                    'account_info'                  => '',
                    'bank_name'                     => $advertisement['bank_name'],
                    'msg'                           => $advertisement['msg'],
                    'sms_verification_required'     => $advertisement['sms_verification_required'],
                    'track_max_amount'              => $advertisement['track_max_amount'],
                    'require_trusted_by_advertiser' => $advertisement['require_trusted_by_advertiser'],
                    'require_identification'        => $advertisement['require_identification'],
                    'min_amount'                    => $advertisement['min_amount'],
                    'max_amount'                    => $advertisement['max_amount'],
                    'limit_to_fiat_amounts'         => $advertisement['limit_to_fiat_amounts'],
                    'payment_window_minutes'        => $advertisement['payment_window_minutes'],
                    'visible'                       => $advertisement['visible']
        ];

        $client   = new Client();
        $endpoint = '/api/ad/' . $id . '/';

        //setting auth-tokens
        $key         = env('LOCAL_HMAC_KEY_' . $this->credential->env_number);
        $hmac_secret = env('LOCAL_HMAC_SECRET_' . $this->credential->env_number);

        //creating nonce
        $mt    = microtime(true);
        $mt    = str_replace('.', '', $mt);
        $nonce = $mt;

        //creating signature
        $message       = $nonce . $key . $endpoint . http_build_query($ad_data);
        $message_bytes = utf8_encode($message);
        $signature     = mb_strtoupper(hash_hmac('sha256', $message_bytes, $hmac_secret));

        // api request
        try {

            $res = $client->request('POST', 'https://localbitcoins.com' . $endpoint, [
                'headers'     => [
                    'Apiauth-Key'       => $key,
                    'Apiauth-Nonce'     => $nonce,
                    'Apiauth-Signature' => $signature
                ],
                'form_params' => $ad_data,
            ]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        $data = json_decode($res->getBody(), true);

        return $data['data']['message'];
    }

    public function assignAd()
    {
        $inputs = request()->all();

        $ad = Advertisement::where('ad_id', $inputs['params']['ad_id'])->first();

        if (isset($ad)) {
            return ['error' => 'There already is a trader working with that advertisement.'];
        }

        //creating localbitcoins contact
        /*  try {
            Advertisement::openContact($inputs['params']['advertisement'], $inputs['params']['amount']);
          } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
          } */

        //creating ad in db
        Advertisement::create($inputs['params']);

        //creating notification

        Pusher::trigger('my-channel', 'notification-to-' . $inputs['params']['trader_id'], [
            'message'   => 'Advertisement' . ' ' . $inputs['params']['ad_id'] . ' has been assigned to you.',
            'trader_id' => $inputs['params']['trader_id']
        ]);

        return 'success';
    }

    public function getWorkroom()
    {
        $ads = Advertisement::where('trader_id', Auth::user()->id)->get();

        //return dd($ads);
        return view('coinbank.traders.workroom')->with(compact('ads'));
    }

    public function openContact($id)
    {
        $advertisement = Advertisement::find($id);

        if (isset($advertisement->contact_id)) {
            return ['error' => 'This advertisement already has a contact.'];
        }

        try {
            $contact_data = Advertisement::openContact($advertisement->ad_id, $advertisement->amount);

            $advertisement->contact_id = $contact_data['data']['contact_id'];
            $advertisement->save();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }

        return redirect('/workroom');
    }

    public function getChat($id)
    {
        $advertisement = Advertisement::find($id);

        /*  $resp = ApiHelper::getLocalBtcV2($this->credential->env_number, '/api/contact_messages/'.$advertisement->contact_id.'/');

          $messages = $resp['message_list']; */

        //return dd($messages);
        return view('coinbank.traders.chat')->with(compact('advertisement'));
    }

    public function getMessages($id, $count)
    {

        $resp = ApiHelper::getLocalBtcV2($this->credential->env_number, '/api/contact_messages/' . $id . '/');

        if ($resp['message_count'] > $count) {
            $ad = Advertisement::where('contact_id', $id)->first();

            Pusher::trigger('my-channel', 'notification-to-' . $ad->trader_id,
                ['message' => 'You have a new message from' . ' ' . $ad->ad_username, 'trader_id' => $ad->trader_id]);
        }

        $this->storeMessages($resp['message_list'], $id);

        return $resp;
    }

    public function getMessagesNot($id)
    {
        $resp = ApiHelper::getLocalBtcV2($this->credential->env_number, '/api/contact_messages/' . $id . '/');

        $this->storeMessages($resp['message_list'], $id);

        return $resp;
    }

    public function sendMessage($id)
    {
        $inputs = request()->all();
        //return dd($inputs);
        $params = ['msg' => $inputs['message'], 'document' => $inputs['file']];

        $resp = ApiHelper::getLocalBtc($this->credential->env_number, '/api/contact_message_post/' . $id . '/', $params,
            [], 'POST');

        //return json_decode($res->getBody(), true)['data'];

        /*  $resp = ApiHelper::postLocalBtc($this->credential->env_number, '/api/contact_message_post/'.$id.'/', ['msg' =>
        $inputs['params']['message']]); */

        return 'success';
    }

    private function storeMessages($messages, $id)
    {
        $count = ContactMessage::where('contact_id', $id)->count();
        foreach ($messages as $key => $msg) {

            if ($key >= $count) {

                $date = new Carbon($msg['created_at']);

                $msg_data = ['contact_id'     => $id,
                             'msg_number'     => $key,
                             'msg'            => $msg['msg'],
                             'lbc_created_at' => $date->format('Y-m-d H:i:s'),
                             'sender'         => $msg['sender']['username']
                ];

                if (isset($msg['attachment_name'])) {
                    $msg_data['attachment_name'] = $msg['attachment_name'];

                    $atachment = ApiHelper::getLocalBtc(1,
                        str_replace('https://localbitcoins.com', '', $msg['attachment_url']), [], [
                            'fileName' => $msg['attachment_name'],
                            'fileType' => $msg['attachment_type']
                        ]);

                } else {
                    $msg_data['attachment_name'] = '';
                }

                ContactMessage::create($msg_data);
            }

        }

        return 'success';
    }

    public function assignAd()
    {
      $inputs = request()->all();

      $ad = Advertisement::where('ad_id', $inputs['params']['advertisement'])->first();

      if (isset($ad)) {
        return ['error' => 'There already is a trader working with that advertisement.'];
      }

      //creating localbitcoins contact
      try {
        Advertisement::openContact($inputs['params']['advertisement'], $inputs['params']['amount']);
      } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
      }

      //creating ad in db
      Advertisement::create(['ad_id' => $inputs['params']['advertisement'], 'trader_id' => $inputs['params']['trader'], 'amount' => $inputs['params']['amount']]);

      //creating notification
      Pusher::trigger('my-channel', 'notification-to-'.$inputs['params']['trader'], ['message' => 'Advertisement'.' '.$inputs['params']['advertisement']. ' has been assigned to you.', 'trader_id' => $inputs['params']['trader']]);

      return 'success';
    }
}
