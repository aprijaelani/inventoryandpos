<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_wo')->nullable();
            $table->string('no_nota')->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->date('tanggal')->nullable();
            $table->date('tanggal_estimasi')->nullable();
            $table->date('tanggal_pengambilan')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('dp')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('pihak_ketiga')->nullable();
            $table->integer('pembayaran')->nullable();
            $table->integer('status'); //Status 1: Pre Order 2: Non Pre Order
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_orders');
    }
}
