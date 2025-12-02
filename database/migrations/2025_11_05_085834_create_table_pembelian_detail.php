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
        Schema::create('t_pembelian_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_pembelian')->constrained('t_pembelian')->onDelete('cascade');
            $table->foreignId('id_produk')->constrained('m_produk')->onDelete('restrict');
            $table->double('jumlah');
            $table->double('jumlah_terima')->default(0);
            $table->date('tanggal_terima')->nullable();
            $table->double('harga');
            $table->double('total');
            $table->foreignId('id_log_stok')->nullable()->constrained('t_log_stok')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_pembelian_detail');
    }
};
