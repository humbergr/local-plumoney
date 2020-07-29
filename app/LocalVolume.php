<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalVolume extends Model
{
  protected $table = 'local_volumes';
  protected $fillable = ['country_code', 'volume'];

  public static function getVolumes()
  {
    return ['ve_vol' => LocalVolume::where('country_code', 'VE')->orderBy('id', 'desc')->first()->volume, 'us_vol' => LocalVolume::where('country_code', 'US')->orderBy('id', 'desc')->first()->volume];
  }
}
