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
        Schema::create('m_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('id_parent')->nullable()->constrained('m_menu')->onDelete('restrict');
            $table->integer('urutan')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_menu');
    }
};
