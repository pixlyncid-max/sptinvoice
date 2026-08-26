<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $socialSettings = [
            [
                'key' => 'social_whatsapp_url',
                'value' => 'https://wa.me/6285711533331',
                'group' => 'Sosial Media & Email Footer',
            ],
            [
                'key' => 'social_instagram_url',
                'value' => 'https://www.instagram.com/konsultanborneo',
                'group' => 'Sosial Media & Email Footer',
            ],
            [
                'key' => 'social_facebook_url',
                'value' => 'https://www.facebook.com/konsultanborneo',
                'group' => 'Sosial Media & Email Footer',
            ],
            [
                'key' => 'social_threads_url',
                'value' => 'https://www.threads.net/@konsultanborneo',
                'group' => 'Sosial Media & Email Footer',
            ],
            [
                'key' => 'social_tiktok_url',
                'value' => 'https://www.tiktok.com/@konsultanborneo',
                'group' => 'Sosial Media & Email Footer',
            ],
        ];

        foreach ($socialSettings as $setting) {
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->where('group', 'Sosial Media & Email Footer')->delete();
    }
};
