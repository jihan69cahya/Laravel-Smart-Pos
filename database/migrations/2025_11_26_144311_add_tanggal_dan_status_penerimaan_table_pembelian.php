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
        Schema::table('t_pembelian', function (Blueprint $table) {
            $table->date('tanggal_penerimaan')->nullable()->after('tanggal');
            $table->tinyInteger('status_penerimaan')->default(0)->after('keterangan')->comment('0 => Belum Diterima, 1 => Sudah Diterima, 2 => Diterima Sebagian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_pembelian', function (Blueprint $table) {
            $table->dropColumn('tanggal_penerimaan');
            $table->dropColumn('status_penerimaan');
        });
    }
};
