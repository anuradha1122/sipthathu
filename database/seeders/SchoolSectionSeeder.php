<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('school_sections')->truncate();

        $data = array(
            array('id' => '1','name' => 'Primary'),
            array('id' => '2','name' => 'Secondary'),
            array('id' => '3','name' => 'Art'),
            array('id' => '4','name' => 'Commerce'),
            array('id' => '5','name' => 'Science'),
            array('id' => '6','name' => 'Technology'),
            array('id' => '7','name' => '13 Years'),
            array('id' => '8','name' => 'Special Education'),
        );

        DB::table('school_sections')->insert($data);
    }
}
