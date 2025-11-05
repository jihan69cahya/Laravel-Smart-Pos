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
        Schema::create('m_produk_harga', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_produk')->constrained('m_produk')->onDelete('cascade');
            $table->datetime('tanggal');
            $table->double('harga');
            $table->double('harga_diskon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_produk_harga');
    }
};
