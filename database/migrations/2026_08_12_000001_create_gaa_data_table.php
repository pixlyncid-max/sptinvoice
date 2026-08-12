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
        Schema::create('gaa_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('npwp')->nullable();
            $table->string('kpp')->nullable();
            $table->string('email')->nullable();
            $table->string('password_email')->nullable();
            $table->string('djp_user')->nullable();
            $table->string('djp_password')->nullable();
            $table->string('user_npwp_16')->nullable();
            $table->string('pic_nik')->nullable();
            $table->string('pic_nama')->nullable();
            $table->string('coretax_password')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('checklist_coretax')->default('Belum');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaa_data');
    }
};
