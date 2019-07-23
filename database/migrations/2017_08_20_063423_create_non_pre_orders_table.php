<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNonPreOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('non_pre_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pre_order_id')->unsigned()->nullable();
            $table->integer('barang_id')->unsigned()->nullable();
            $table->integer('qty');
            $table->double('harga_non_po');
            $table->timestamps();
            $table->softDeletes();



            $table->foreign('pre_order_id')->references('id')->on('pre_orders')->onDelete('no action');
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
        Schema::dropIfExists('non_pre_orders');
    }
}
