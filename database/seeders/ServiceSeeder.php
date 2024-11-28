<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->truncate();

        $data = [
            ['id' => 1, 'name' => 'SLTS-Teacher Service'],
            ['id' => 2, 'name' => 'SLTES-Teacher Educator Service'],
            ['id' => 3, 'name' => 'SLPS-Principal Service'],
            ['id' => 4, 'name' => 'SLEAS-Edu Administrator Service'],
            ['id' => 5, 'name' => 'SLAS-Administrator Service'],
            ['id' => 6, 'name' => 'SLPS-Planning Service'],
            ['id' => 7, 'name' => 'SLES-Engineering Service'],
            ['id' => 8, 'name' => 'SLAS-Accounting Service'],
            ['id' => 9, 'name' => 'ITCA-IT Service'],
            ['id' => 10, 'name' => 'Office Management Service'],
            ['id' => 11, 'name' => 'Dev Officer Service'],
        ];

        DB::table('services')->insert($data);
    }
}
