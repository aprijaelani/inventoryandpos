<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaksiPindahGudangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_pindah_gudangs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pindah_gudang_id')->unsigned()->nullable();
            $table->integer('barang_id')->unsigned()->nullable();
            $table->integer('qty')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pindah_gudang_id')->references('id')->on('pindah_gudangs')->onDelete('no action');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksi_pindah_gudangs');
    }
}
