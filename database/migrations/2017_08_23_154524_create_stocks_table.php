<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('barang_id')->unsigned()->nullable();
            $table->integer('gudang_id')->unsigned()->nullable();
            $table->integer('qty');
            $table->integer('last_qty');
            $table->double('harga_pokok');
            $table->timestamps();
            $table->softDeletes();



            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('no action');
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
