<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_po')->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('gudang_id')->unsigned()->nullable();
            $table->integer('no_sj')->nullable();
            $table->date('tanggal')->nullable();
            $table->date('tanggal_estimasi')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('pembayaran')->nullable();
            $table->integer('status'); //Status 1: Pre Order 2: Non Pre Order
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('gudang_id')->references('id')->on('gudangs')->onDelete('no action');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_orders');
    }
}
