<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ETH has 18 places of decimal precision, BTT can go up to 900b, 
        //  play it safe
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->float('rate',32, 18)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->float('rate',8, 8)->change();
        });
    }
}