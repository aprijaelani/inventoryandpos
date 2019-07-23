<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreOrderInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_order_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('penerimaan_barang_id')->unsigned()->nullable();
            $table->integer('pre_order_out_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('barang_id')->unsigned()->nullable();
            $table->integer('qty');
            $table->double('harga_po');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('penerimaan_barang_id')->references('id')->on('penerimaan_barangs')->onDelete('no action');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('pre_order_out_id')->references('id')->on('pre_order_outs')->onDelete('no action');
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
        Schema::dropIfExists('pre_order_ins');
    }
}
