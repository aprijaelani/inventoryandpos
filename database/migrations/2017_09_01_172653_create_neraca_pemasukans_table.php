<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeracaPemasukansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neraca_pemasukans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('neraca_id')->unsigned()->nullable();
            $table->integer('total');
            $table->string('keterangan');
            $table->integer('keterangan_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('neraca_id')->references('id')->on('neraca_keuangans')->onDelete('no action');
            $table->foreign('keterangan_id')->references('id')->on('biaya_pendapatans')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neraca_pemasukans');
    }
}
