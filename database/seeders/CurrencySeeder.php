<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Jobs\FetchPrices;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        DB::table('currency_rates')->truncate();
        DB::table('currencies')->truncate();
        DB::table('currency_types')->truncate();
        DB::statement("SET foreign_key_checks=1");

        DB::table('currency_types')->insert([
        	['id' => 1, 'name' => 'fiat'],
        	['id' => 2, 'name' => 'crypto']
        ]);

        DB::table('currencies')->insert([
        	['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar', 'api_code' => 'usd', 'type_id' => 1],
        	['code' => 'GBP', 'symbol' => '£', 'name' => 'Pound Sterling', 'api_code' => 'gbp', 'type_id' => 1],
        	['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro', 'api_code' => 'eur', 'type_id' => 1],
        	['code' => 'BTC', 'symbol' => '₿', 'name' => 'Bitcoin', 'api_code' => 'bitcoin', 'type_id' => 2],
        	['code' => 'ETH', 'symbol' => 'Ξ', 'name' => 'Ethereum', 'api_code' => 'ethereum', 'type_id' => 2],
        	['code' => 'LTC', 'symbol' => 'Ł', 'name' => 'Litecoin', 'api_code' => 'litecoin', 'type_id' => 2],
            ['code' => 'DOGE', 'symbol' => 'Ɖ', 'name' => 'Dogecoin', 'api_code' => 'dogecoin', 'type_id' => 2],
            ['code' => 'GRT', 'symbol' => 'G', 'name' => 'The Graph', 'api_code' => 'the_graph', 'type_id' => 2],
            ['code' => 'XLM', 'symbol' => 'S', 'name' => 'Stellar', 'api_code' => 'stellar', 'type_id' => 2]
        ]);
        FetchPrices::dispatch();
    }
}
