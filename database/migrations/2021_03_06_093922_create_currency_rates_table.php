<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->bigInteger('primary_currency_id')->unsigned();
            $table->bigInteger('secondry_currency_id')->unsigned();
            $table->float('rate',8, 8)->nullable();
            $table->datetime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        Schema::table('currency_rates', function (Blueprint $table) {
            $table->foreign('primary_currency_id')->unsigned()->references('id')->on('currencies')
                ->onDelete('cascade');
            $table->foreign('secondry_currency_id')->unsigned()->references('id')->on('currencies')
                ->onDelete('cascade');
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
            $table->dropForeign(['primary_currency_id']);
            $table->dropForeign(['secondry_currency_id']);
        });

        Schema::dropIfExists('currency_rates');
    }
}
