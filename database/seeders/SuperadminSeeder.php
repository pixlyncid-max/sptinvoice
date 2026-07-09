<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use App\Models\PenawaranTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Superadmin if not exists
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );

        // 2. Default Settings
        $settings = [
            // Payroll Variables (%)
            ['key' => 'bpjs_kesehatan_percent', 'value' => '1', 'group' => 'payroll'],
            ['key' => 'bpjs_tk_percent', 'value' => '2', 'group' => 'payroll'],
            ['key' => 'pph21_percent', 'value' => '5', 'group' => 'payroll'],
            
            // Penalties (Fixed Amount)
            ['key' => 'late_penalty', 'value' => '50000', 'group' => 'penalty'],
            ['key' => 'permit_penalty', 'value' => '100000', 'group' => 'penalty'],
            ['key' => 'sick_penalty', 'value' => '0', 'group' => 'penalty'],
            
            // Overtime Rates
            ['key' => 'overtime_weekday_rate', 'value' => '25000', 'group' => 'overtime'],
            ['key' => 'overtime_weekend_rate', 'value' => '50000', 'group' => 'overtime'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 3. Default Penawaran Templates
        PenawaranTemplate::updateOrCreate(
            ['code' => 'perizinan'],
            [
                'name' => 'Template Perizinan',
                'tujuan' => 'Tujuan dari penugasan ini adalah untuk membantu dalam memastikan seluruh perizinan...',
                'lingkup' => 'Adapun ruang lingkup pekerjaan yang kami tawarkan meliputi pembuatan akun Coretax...',
                'jenis_pekerjaan_intro' => 'Dalam rangka mendukung operasional kegiatan usaha, kami menyediakan...',
                'prasyarat' => 'Agar pekerjaan di atas dapat berjalan efektif, maka diperlukan prasyarat...',
                'penutup' => 'Demikian surat penawaran ini kami sampaikan...',
            ]
        );
    }
}
