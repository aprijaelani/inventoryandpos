<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiStockMasuksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_stock_masuks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_masuk_id')->unsigned()->nullable();
            $table->integer('stock_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stock_masuk_id')->references('id')->on('stock_masuks')->onDelete('no action');
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('no action');        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_stock_masuks');
    }
}
