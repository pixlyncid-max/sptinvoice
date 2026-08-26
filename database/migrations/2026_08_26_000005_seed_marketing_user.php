<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exists = DB::table('users')->where('email', 'marketing@worklync.id')->exists();

        if (!$exists) {
            DB::table('users')->insert([
                'name' => 'Marketing',
                'email' => 'marketing@worklync.id',
                'password' => Hash::make('password'),
                'role' => 'marketing',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('email', 'marketing@worklync.id')->delete();
    }
};
