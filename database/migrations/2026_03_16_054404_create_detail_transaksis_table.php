<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_transaksi');
            $table->unsignedBigInteger('id_barang');
            $table->integer('jumlah');
            $table->integer('subtotal');
            $table->foreign('id_transaksi')
                ->references('id_transaksi')
                ->on('transaksis')
                ->onDelete('cascade');
            $table->foreign('id_barang')
                ->references('id_barang')
                ->on('barangs')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
