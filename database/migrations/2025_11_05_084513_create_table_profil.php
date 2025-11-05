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
        Schema::create('m_profil', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_toko');
            $table->string('nama_aplikasi');
            $table->string('email');
            $table->string('telp');
            $table->text('alamat');
            $table->string('logo');
            $table->string('icon');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_profil');
    }
};
