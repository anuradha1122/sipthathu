<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentTerminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('appointment_terminations')->truncate();
        $data = [
            ['id' => 1, 'name' => 'Transfer to another school'],
            ['id' => 2, 'name' => 'Pension'],
            ['id' => 3, 'name' => 'Service Termination'],
            ['id' => 4, 'name' => 'Death'],
        ];

        DB::table('appointment_terminations')->insert($data);
    }
}
