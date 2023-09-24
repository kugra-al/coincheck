<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CurrencyType;
use App\Models\CurrencyRate;
use App\Models\Currency;

class IndexController extends Controller
{
    public function index()
    {
    	$fiat = CurrencyType::with('currency')->where('name','fiat')->first();
    	$crypto = CurrencyType::with('currency')->where('name','crypto')->first();

    	return view('index', ['fiat'=>$fiat, 'crypto'=>$crypto]);
    }

    public function fetchPrices()
    {
        $rates = CurrencyRate::getRates();
        $currencies = Currency::getWithNamesCodesAndSymbols();
    	return response()->json(["content"=>['rates'=>$rates, 'currencies'=>$currencies]],200);
    }
}
