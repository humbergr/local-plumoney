<?php

namespace App;

use GuzzleHttp\Client;
use App\BitstampData;
use App\Country;
use Carbon\Carbon;

class MarketData
{
  public static function indexData()
  {
    $client = new Client();
    $res = $client->request('GET', 'https://localbitcoins.com/buy-bitcoins-online/VES/.json');
    $data = json_decode($res->getBody(), true);
    return $data;
  }

  public static function getAdvertisements($url)
  {
    $client = new Client();
    $res = $client->request('GET', $url);
    $data = json_decode($res->getBody(), true);
    return $data;
  }

  public static function trickerAll()
  {
    $client = new Client();
    $res = $client->request('GET', 'https://localbitcoins.com/bitcoinaverage/ticker-all-currencies/');
    $data = json_decode($res->getBody(), true);
    return $data;
  }

  public function check500($fiat,$tid = null){
    $client = new Client();
    $res = $client->request('GET', 'https://localbitcoins.com/bitcoincharts/'.$fiat.'/trades.json?max_tid='.$tid);
    $data = json_decode($res->getBody(), true);
    return $data;
  }

  public static function getVolumeT($code)
  {
    $fiat = \DB::select('select currency from roboto_playground.countries where iso2="'.$code.'"');
    $_this = new self;
    $check500 = $_this->check500($fiat[0]->currency,null);
    $ads = [];
    while ($check500[499]['date']>strtotime('-1 day', time())) {
      $ads = array_merge($ads, $check500);
      $check500 = $_this->check500($fiat[0]->currency,$check500[499]['tid']);
    }
    $total = 0;
    foreach ($ads as $ad) {
      $total+= $ad['amount'];
    }
    return $total;
  }

  public static function getVolumeB($code, $name)
  {
    $client = new Client();
    $ads = [];
    $res = $client->request('GET', 'https://localbitcoins.com/buy-bitcoins-online/'.$code.'/'.$name.'/.json');
    $data = json_decode($res->getBody(), true);
    $ads = $data['data']['ad_list'];

    while (isset($data['pagination']['next'])) {
      $res = $client->request('GET', $data['pagination']['next']);
      $data = json_decode($res->getBody(), true);
      $ads = array_merge($ads, $data['data']['ad_list']);
    }
    $total = 0;
    foreach ($ads as $ad) {
      $total+= $ad['data']['max_amount_available'] / $ad['data']['temp_price'];
    }
    return $total;
  }
  
  public static function getVolumeS($code, $name)
  {
    $client = new Client();
    $ads = [];
    $res = $client->request('GET', 'https://localbitcoins.com/sell-bitcoins-online/'.$code.'/'.$name.'/.json');
    $data = json_decode($res->getBody(), true);
    $ads = $data['data']['ad_list'];

    while (isset($data['pagination']['next'])) {
      $res = $client->request('GET', $data['pagination']['next']);
      $data = json_decode($res->getBody(), true);
      $ads = array_merge($ads, $data['data']['ad_list']);
    }
    $total = 0;
    foreach ($ads as $ad) {
      $total+= $ad['data']['max_amount_available'] / $ad['data']['temp_price'];
    }
    return $total;
  }

  public static function paymentMethods(){
    $client = new Client();
    $res = $client->request('GET', 'https://localbitcoins.com/api/payment_methods/');
    $data = json_decode($res->getBody(), true);
    return $data['data']['methods'];
  }

  public static function getExchangeRates()
  {
      $opexchID = env('OPEXCH_ID');
      $client   = new Client();
      $res      = $client->request(
          'GET',
          'https://openexchangerates.org/api/latest.json',
          [
              'query' => ['app_id' => $opexchID]
          ]
      )
          ->getBody()
          ->getContents();

      return json_decode($res, true)['rates'];
  }
}
