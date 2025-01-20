<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassMediumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('class_media')->truncate();
        $data = [
            ['id' => 1, 'name' => 'Sinhala'],
            ['id' => 2, 'name' => 'Tamil'],
            ['id' => 3, 'name' => 'English'],
            ['id' => 4, 'name' => 'Sinhala/English'],
            ['id' => 5, 'name' => 'Tamil/English'],
            ['id' => 6, 'name' => 'Sinhala/Tamil'],
            ['id' => 7, 'name' => 'Sinhala/Tamil/English'],
        ];

        DB::table('class_media')->insert($data);
    }
}
