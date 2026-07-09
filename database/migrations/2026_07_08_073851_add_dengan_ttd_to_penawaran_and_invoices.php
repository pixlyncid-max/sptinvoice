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
            $table->boolean('dengan_ttd')->default(true)->after('diskon');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('dengan_ttd')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penawaran', function (Blueprint $table) {
            $table->dropColumn('dengan_ttd');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('dengan_ttd');
        });
    }
};
