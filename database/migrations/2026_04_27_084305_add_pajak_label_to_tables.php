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
        Schema::table('penawaran', function (Blueprint $table) {
            $table->string('pajak_label')->default('Pajak')->after('pajak_persen');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('pajak_label')->default('Pajak')->after('pajak_persen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            $table->dropColumn('pajak_label');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('pajak_label');
        });
    }
};
