<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyListController extends Controller
{
    public function index() {
    	$currencies = Currency::with('type')->paginate();
    	return view('currencies',['currencies'=>$currencies]);
    }
}
