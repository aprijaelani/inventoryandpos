<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiStockOpnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_stock_opnames', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stock_opname_id')->unsigned()->nullable();
            $table->integer('barang_id')->unsigned()->nullable();
            $table->integer('stock_id')->unsigned()->nullable();
            $table->integer('selisih');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('stock_opname_id')->references('id')->on('stock_opnames')->onDelete('no action');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('no action');
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
        Schema::dropIfExists('transaksi_stock_opnames');
    }
}
