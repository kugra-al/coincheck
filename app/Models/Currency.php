<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function type()
    {
    	return $this->belongsTo('App\Models\CurrencyType');
    }

    public function rates()
    {
    	return $this->hasMany('App\Models\CurrencyRate','primary_currency_id');
    }

    public static function getWithNamesCodesAndSymbols()
    {
        $tmp = [];
        foreach(Currency::select('code', 'name', 'symbol')->get() as $cur) {
            $tmp[$cur->code] = ['code' => $cur->code, 'name' => $cur->name, 'symbol' => $cur->symbol];
        }
        return $tmp;
    }
}
