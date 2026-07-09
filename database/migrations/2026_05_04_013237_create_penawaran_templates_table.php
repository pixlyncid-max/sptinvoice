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
        Schema::create('penawaran_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g. perizinan, keuangan, digital
            $table->text('tujuan')->nullable();
            $table->text('lingkup')->nullable();
            $table->text('jenis_pekerjaan_intro')->nullable();
            $table->text('prasyarat')->nullable();
            $table->text('penutup')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penawaran_templates');
    }
};
