<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingRefinementSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // BPJS
            ['key' => 'bpjs_kesehatan_percent', 'value' => '1', 'group' => 'payroll'],
            ['key' => 'bpjs_tk_percent', 'value' => '3', 'group' => 'payroll'],
            
            // Penalties
            ['key' => 'permit_penalty', 'value' => '181909', 'group' => 'penalty'],
            ['key' => 'sick_penalty', 'value' => '15909', 'group' => 'penalty'],
            
            // Overtime
            ['key' => 'overtime_weekday_rate', 'value' => '30000', 'group' => 'overtime'], // 1st hour
            ['key' => 'overtime_weekday_extra_rate', 'value' => '40000', 'group' => 'overtime'], // hour 2-4
            ['key' => 'overtime_weekend_rate', 'value' => '40000', 'group' => 'overtime'], // per hour
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
