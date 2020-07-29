<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\LocalTrades;


class LocalTradesController extends Controller
{
    public function postClosedTrades(){
      $trades = LocalTrades::getClosedTrades();
      return $trades;
    }

    public function postCompletedTrades(){
      $trades = LocalTrades::getCompletedTrades();
      return $trades;
    }

    public function postCancelledTrades(){
      $trades = LocalTrades::getCancelledTrades();
      return $trades;
    }

    public function tradesPage(){
      $url = request()->input('url');
      $data = LocalTrades::tradesPage($url);
      return $data;
    }
}
