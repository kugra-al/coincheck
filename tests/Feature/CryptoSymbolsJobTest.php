<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CryptoSymbolsJobTest extends TestCase
{
    public function testJobRunsWithoutError()
    {
        \App\Jobs\FetchCryptoSymbols::dispatch();
    }
}
