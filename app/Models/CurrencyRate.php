<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $primaryKey = null;
    public $fillable = ['primary_currency_id', 'secondry_currency_id', 'rate', 'updated_at'];

    // public function setUpdatedAtAttribute($value)
    // {
    // 	$this->attributes['updated_at'] = \Carbon\Carbon::now();
    // }

    public function crypto() 
    {
    	return $this->belongsTo('App\Models\Currency','primary_currency_id');
    }

    public function fiat() 
    {
    	return $this->belongsTo('App\Models\Currency','secondry_currency_id');
    }

    public static function getRates()
    {
    	$rates = CurrencyRate::with('fiat','crypto')->get();
		$msg = [];
		$timezone = date_default_timezone_get();
       
		foreach($rates as $rate) {
			if (!isset($msg[$rate->crypto->code])) 
				$msg[$rate->crypto->code] = [];

			$msg[$rate->crypto->code][$rate->fiat->code] = ['rate'=>$rate->rate,
				'update_time'=>$rate->updated_at->format('Y-m-d H:i:s')." $timezone",
				'fetch_time'=>$rate->fetched_at." $timezone"
			];
		}
		return $msg;
    }
}
