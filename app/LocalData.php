<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LocalData extends Model
{
  protected $table = 'local_datas';
  protected $fillable = ['bs1h', 'bs6h', 'bs12h', 'bs24h', 'us1h', 'us6h', 'us12h', 'us24h'];

  public function arrayData(){
    return ['VES' => ['avg_1h' => $this->bs1h, 'avg_6h' => $this->bs6h, 'avg_12h' => $this->bs12h, 'avg_24h' => $this->bs24h],  'USD' => ['avg_1h' => $this->us1h,  'avg_6h' => $this->us6h,  'avg_12h' => $this->us12h,  'avg_24h' => $this->us24h]];
  }
}
