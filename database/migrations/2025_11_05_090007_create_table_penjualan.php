<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_penjualan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_pelanggan')->nullable()->constrained('m_pelanggan')->onDelete('restrict');
            $table->foreignId('id_kasir')->constrained('users')->onDelete('restrict');
            $table->string('no_faktur');
            $table->date('tanggal');
            $table->double('sub_total');
            $table->double('pajak');
            $table->double('potongan');
            $table->double('biaya_tambahan');
            $table->double('total_tagihan');
            $table->double('total_bayar');
            $table->double('kembalian');
            $table->foreignId('id_metode_bayar')->constrained('m_metode_bayar')->onDelete('restrict');
            $table->double('poin_member');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan');
    }
};
