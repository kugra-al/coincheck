<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Http;
use Cache;
use App\Models\Currency;
use App\Models\CurrencyType;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class FetchCryptoSymbols implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $fiat = [];
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Coingecko uses ISO 4217 for fiat currencies and crypto names for cryptocurrencies
        // https://www.coingecko.com/en/api#operations-simple-get_simple_price
        $fiat = CurrencyType::with('currency')->where('name','fiat')->first();
        foreach($fiat->currency as $f) {
            $this->fiat[strtolower($f->code)] = $f;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fiat = $this->fiat;
        // Cache so we only do this max once every 1500 seconds
        Cache::remember('priceUpdate', 5, function() use($fiat)
        {
            $client = new CoinGeckoClient();
            foreach($this->fiat as $code=>$fiat) {
                // Get top 250
                return;
                $response = $client->coins()->getMarkets($code,['order'=>'market_cap_desc','per_page'=>250]);
                
                if ($response && sizeof($response)) {
                    $json = $response;
                    foreach($json as $crypto) {
                        if (isset($crypto['id'])) {
                            $check = Currency::where('code',strtoupper($crypto['symbol']))->first();

                            if (!$check) {
                                $currency = new Currency;
                                $currency->code = strtoupper($crypto['symbol']);
                                $currency->name = $crypto['name'];
                                $currency->api_code = $crypto['id'];
                                $currency->symbol = utf8_encode(substr($crypto['name'],0,1));
                                $currency->type_id = 2;
                                $currency->save();
                            }  
                        }
                    }
                } 
            }
        });
    }
}
