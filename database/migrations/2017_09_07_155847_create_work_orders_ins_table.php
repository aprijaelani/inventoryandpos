<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrdersInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('work_order_id')->unsigned()->nullable();
            $table->integer('sales_id')->unsigned()->nullable();
            $table->integer('barang_id')->unsigned()->nullable();
            $table->integer('qty');
            $table->double('harga_wo');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('work_order_id')->references('id')->on('work_orders')->onDelete('no action');
            $table->foreign('sales_id')->references('id')->on('employees')->onDelete('no action');
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
        Schema::dropIfExists('work_orders_ins');
    }
}
