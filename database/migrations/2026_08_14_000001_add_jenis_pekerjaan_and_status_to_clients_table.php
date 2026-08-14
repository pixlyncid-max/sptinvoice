<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('perusahaan')->nullable(false)->change();
            $table->string('email')->nullable()->change();
            $table->enum('jenis_pekerjaan', ['Satuan', 'Bulanan', 'Tahunan'])->default('Satuan')->after('alamat');
            $table->enum('status', ['Aktif', 'Non Aktif', 'Pending'])->default('Aktif')->after('jenis_pekerjaan');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('perusahaan')->nullable()->change();
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['jenis_pekerjaan', 'status']);
        });
    }
};
