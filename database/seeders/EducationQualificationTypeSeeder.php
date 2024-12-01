<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationQualificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('education_qualification_types')->truncate();
        $data = [

            ['id' => 1, 'name' => 'No Degree'],
            ['id' => 2, 'name' => 'Undergraduate Degrees'],
            ['id' => 3, 'name' => 'Graduate Degrees'],
            ['id' => 4, 'name' => 'Postgraduate and Doctoral Degrees'],
            ['id' => 5, 'name' => 'Specialized/Professional Degrees'],
        ];

        DB::table('education_qualification_types')->insert($data);
    }
}
