<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\BtcBssData;
use App\UsdBssData;


class TradersDataController extends Controller
{
  public function updateData()
  {
    $inputs = request()->all();

    if ($inputs['usd_bss'] != '' && $inputs['usd_bss'] != 0) {
      UsdBssData::create(['price' => $inputs['usd_bss']]);
    } else {
      return ['status' => 'error', 'error' => 'Please type a valid price.'];
    }

    if ($inputs['btc_bss'] != '' && $inputs['btc_bss'] != 0) {
      BtcBssData::create(['price' => $inputs['btc_bss']]);
    } else {
      return ['status' => 'error', 'error' => 'Please type a valid price.'];
    }

    return ['status' => 'success'];
  }
}
