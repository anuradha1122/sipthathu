<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointment_categories')->truncate();

        $data = [
            ['id' => 1, 'name' => 'Sinhala'],
            ['id' => 2, 'name' => 'Tamil'],
            ['id' => 3, 'name' => 'English'],
        ];

        DB::table('appointment_categories')->insert($data);
    }
}
