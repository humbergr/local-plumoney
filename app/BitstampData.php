<?php

namespace App;
use Carbon\Carbon;
use GuzzleHttp\Client;

use Illuminate\Database\Eloquent\Model;

class BitstampData extends Model
{
  protected $table = 'bitstamp_datas';
  protected $fillable = ['price', 'hour', 'created_at'];

  public static function getData()
  {
    $client = new Client();
    $res = $client->request('GET', 'https://www.bitstamp.net/api/ticker/');
    $data = json_decode($res->getBody(), true);

    $p_24h =  BitstampData::where('hour', Carbon::now()->format('H'))
                          ->where('created_at', '>', Carbon::now()->subHours(25))
                          ->where('created_at', '<', Carbon::now()->subHours(2))
                          ->orderBy('id', 'DESC')
                          ->first();

    $p_12h = BitstampData::where('hour', abs(Carbon::now()->format('H') - 12))
                          ->orderBy('id', 'DESC')
                          ->where('created_at', '>', Carbon::now()->subHours(13))
                          ->first();

    $p_6h = BitstampData::where('hour', abs(Carbon::now()->format('H') - 6))
                          ->orderBy('id', 'DESC')
                          ->where('created_at', '>', Carbon::now()->subHours(7))
                          ->first();

    $p_1h = BitstampData::where('hour', abs(Carbon::now()->format('H') - 1))
                          ->orderBy('id', 'DESC')
                          ->where('created_at', '>', Carbon::now()->subHours(2))
                          ->first();

    return ['p_24h' => $p_24h, 'p_12h' => $p_12h, 'p_6h' => $p_6h, 'p_1h' => $p_1h, 'bitNow' => $data['last']];
  }

  public static function getNow()
  {
    $client = new Client();
    $res = $client->request('GET', 'https://www.bitstamp.net/api/ticker/');
    $data = json_decode($res->getBody(), true);

    return $data['last'];
  }
}
