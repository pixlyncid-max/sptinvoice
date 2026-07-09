<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Staff Keuangan', 'division' => 'keuangan_perpajakan', 'salary' => 5000000],
            ['name' => 'Staff Perpajakan', 'division' => 'keuangan_perpajakan', 'salary' => 5000000],
            ['name' => 'Staff Legal', 'division' => 'perizinan', 'salary' => 5000000],
            ['name' => 'Staff Digital Marketing', 'division' => 'digital_marketing', 'salary' => 5000000],
            ['name' => 'Staff IT', 'division' => 'digital_marketing', 'salary' => 5000000],
        ];

        foreach ($data as $item) {
            $division = Division::where('name', $item['division'])->first();
            
            if ($division) {
                Position::updateOrCreate(
                    ['name' => $item['name'], 'division_id' => $division->id],
                    ['base_salary' => $item['salary']]
                );
            } else {
                // If division not found, just create the position without division for now
                Position::updateOrCreate(
                    ['name' => $item['name']],
                    ['base_salary' => $item['salary']]
                );
            }
        }
    }
}
