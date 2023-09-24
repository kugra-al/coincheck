<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchPricesJobTest extends TestCase
{
    public function testJobRunsWithoutError()
    {
        \App\Jobs\FetchPrices::dispatch();
    }

    /*public function testPostFetchPricesTriggersJob()
    {
        $this->expectsJobs(\App\Jobs\FetchPrices::class);
        $this->post('/fetchPrices');      
    }*/

    public function testFetchPricesJobTriggersPriceUpdateEvent()
    {
        $this->expectsEvents(\App\Events\PriceUpdateEvent::class);
        \App\Jobs\FetchPrices::dispatchNow();
    }

    public function testAllCryptoCurrenciesHaveFiatRates()
    {
        $currencies = \App\Models\Currency::where('type_id',2)->with('rates')->get();
        $fiats = \App\Models\Currency::where('type_id',1)->get()->count();
        foreach($currencies as $currency) {
            dump($currency->id);
            $this->assertCount($fiats,$currency->rates);
            fwrite(STDERR, print_r($currency->id, TRUE));
        }
    }
}
