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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('nik')->nullable()->after('nama');
            $table->string('grade')->nullable()->after('jabatan');
            $table->date('tgl_masuk')->nullable()->after('grade');
            $table->string('bank')->nullable()->after('tgl_masuk');
            $table->string('no_rekening')->nullable()->after('bank');
            $table->string('nama_rekening')->nullable()->after('no_rekening');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['nik', 'grade', 'tgl_masuk', 'bank', 'no_rekening', 'nama_rekening']);
        });
    }
};
