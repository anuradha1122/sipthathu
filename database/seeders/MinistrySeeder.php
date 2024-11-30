<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MinistrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ministries')->truncate();

        $data = array(
            array('workPlaceId' => '1','officeId' => NULL,'ministryNo' => 'M1'),
            array('workPlaceId' => '2','officeId' => '11','ministryNo' => 'M2'),
            array('workPlaceId' => '3','officeId' => '12','ministryNo' => 'M3'),
            array('workPlaceId' => '4','officeId' => '13','ministryNo' => 'M4'),
            array('workPlaceId' => '5','officeId' => '14','ministryNo' => 'M5'),
            array('workPlaceId' => '6','officeId' => '15','ministryNo' => 'M6'),
            array('workPlaceId' => '7','officeId' => '16','ministryNo' => 'M7'),
            array('workPlaceId' => '8','officeId' => '17','ministryNo' => 'M8'),
            array('workPlaceId' => '9','officeId' => '18','ministryNo' => 'M9'),
            array('workPlaceId' => '10','officeId' => '19','ministryNo' => 'M10')
          );

        DB::table('ministries')->insert($data);
    }
}
