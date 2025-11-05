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
        Schema::create('t_mapping_menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_role')->constrained('m_role')->onDelete('restrict');
            $table->foreignId('id_menu')->constrained('m_menu')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_mapping_menu');
    }
};
