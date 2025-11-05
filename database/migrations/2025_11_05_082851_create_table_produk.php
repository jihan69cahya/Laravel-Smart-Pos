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
        Schema::create('m_produk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_kategori')->constrained('m_kategori')->onDelete('restrict');
            $table->foreignId('id_satuan')->constrained('m_satuan')->onDelete('restrict');
            $table->string('kode');
            $table->string('nama');
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('stok_minimal')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_produk');
    }
};
