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
        Schema::create('t_stok_opname', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal');
            $table->foreignId('id_produk')->constrained('m_produk')->onDelete('cascade');
            $table->double('stok');
            $table->double('fisik');
            $table->double('selisih');
            $table->text('keterangan')->nullable();
            $table->foreignId('id_log_stok')->constrained('t_log_stok')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stok_opname');
    }
};
