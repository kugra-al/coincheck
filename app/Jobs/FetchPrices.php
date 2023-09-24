<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\CurrencyType;
use App\Models\CurrencyRate;
use Cache;
use DB;
use Schema;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;

class FetchPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $fiat = [];
    private $crypto = [];

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
        $crypto = CurrencyType::with('currency')->where('name','crypto')->first();
        foreach($crypto->currency as $c) {
            $this->crypto[strtolower(str_replace(" ","-",$c->api_code))] = $c;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crypto = $this->crypto;
        $fiat = $this->fiat;
        // Cache so we only do this max once every 5 seconds
        Cache::remember('priceUpdate', 5, function() use($crypto, $fiat)
        {
            $client = new CoinGeckoClient();
            
            $args = ['ids'=>array_keys($crypto), 'vs_currencies'=>array_keys($fiat)];
            $response = $client->simple()->getPrice(implode(',',$args['ids']),implode(',',$args['vs_currencies']),
                ['include_last_updated_at'=>"true"]);
           
            if ($response) {
                $json = $response;

                $inserts = array();
                foreach(array_keys($this->crypto) as $crypto) {
                    if (isset($json[$crypto])) {
                        foreach(array_keys($this->fiat) as $fiat) {
                            if (isset($json[$crypto][$fiat])) {
                                $inserts[] = [
                                    'primary_currency_id' => $this->crypto[$crypto]->id,
                                    'secondry_currency_id' => $this->fiat[$fiat]->id,
                                    'rate' => $json[$crypto][$fiat],
                                    'updated_at' => \Carbon\Carbon::createFromTimestamp(
                                        $json[$crypto]['last_updated_at'])->toDateTimeString(),
                                    'fetched_at' => now()
                                ];                                         
                            }
                        }
                    }
                }
                if (sizeof($inserts)) {
                    // Upsert would be best here, but needs primary or unique keys
                    Schema::disableForeignKeyConstraints();
                    DB::table('currency_rates')->truncate();
                    Schema::enableForeignKeyConstraints();
                    CurrencyRate::insert($inserts);
                }
            }
            return $response;
        });
        
        event(new \App\Events\PriceUpdateEvent());
        return;
    }
}
