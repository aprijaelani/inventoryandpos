<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_supplier')->nullable();
            $table->integer('supplier_id')->unsigned()->nullable();
            $table->string('kode_barcode');
            $table->string('nama_barang');
            $table->integer('grouping_id')->unsigned()->nullable();
            $table->integer('status');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('no action');
            $table->foreign('grouping_id')->references('id')->on('groupings')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barangs');
    }
}
