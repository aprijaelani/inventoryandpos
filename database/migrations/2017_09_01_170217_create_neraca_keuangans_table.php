<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNeracaKeuangansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neraca_keuangans', function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->integer('total');
            $table->string('last_total');
            $table->integer('status'); //Status 1:Pemasukkan 2:Pengeluaran
            $table->integer('status_pembayaran'); //1. Cash 2. Non Cash
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neraca_keuangans');
    }
}
