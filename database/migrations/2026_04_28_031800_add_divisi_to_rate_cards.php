<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rate_cards', function (Blueprint $table) {
            $table->string('divisi', 50)->default('digital_marketing')->after('id');
            $table->string('sub_kategori')->nullable()->after('divisi');
            // client_id already nullable, we just stop using it
        });
    }

    public function down(): void
    {
        Schema::table('rate_cards', function (Blueprint $table) {
            $table->dropColumn(['divisi', 'sub_kategori']);
        });
    }
};
