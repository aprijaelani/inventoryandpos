<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('penerimaan_barang_id')->unsigned()->nullable();
            $table->double('total_harga');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('penerimaan_barang_id')->references('id')->on('penerimaan_barangs')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
