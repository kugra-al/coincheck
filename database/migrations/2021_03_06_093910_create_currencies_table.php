<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('symbol');
            $table->string('name');
            $table->bigInteger('type_id')->unsigned();
        });

        Schema::table('currencies', function (Blueprint $table) {
            $table->foreign('type_id')->unsigned()->references('id')->on('currency_types')
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
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropForeign(['type_id']);
        });
        
        Schema::dropIfExists('currencies');
    }
}
