<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            // Drop foreign key if it exists
            if (Schema::hasColumn('positions', 'division_id')) {
                $table->dropForeign(['division_id']);
                $table->dropColumn('division_id');
            }
            
            if (Schema::hasColumn('positions', 'base_salary')) {
                $table->dropColumn('base_salary');
            }
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->foreignId('division_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('base_salary', 15, 2)->default(0);
        });
    }
};
