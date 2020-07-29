<?php

namespace App\Http\Controllers;

use App\LocalWallet;
use App\CurrencyWallet;
use Illuminate\Http\Request;

class LocalWalletController extends Controller
{
    public function walletsData()
    {
      $usd = CurrencyWallet::where('currency', 'USD')->first();
      $btc = LocalWallet::walletBalance();

      return ['usd' => $usd->balance, 'btc' => $btc];
    }
}
