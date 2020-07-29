<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
  protected $table = 'countries';
  protected $fillable = ['name', 'code'];

  public static function countryArray()
  {
    $countries = self::all();
    $result = [];
    $ind = [];

    foreach ($countries as $country) {
      $ind['text'] = ucfirst($country->name);
      $ind['value'] = $country->code;
      array_push($result, $ind);
    }

    return $result;
  }
}
