<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('positions')->truncate();

        $data = [
            // School Positions
            ['id' => 1, 'name' => 'School Position 1', 'workPlaceCatagoryId' => 1],
            ['id' => 2, 'name' => 'School Position 2', 'workPlaceCatagoryId' => 1],
            ['id' => 3, 'name' => 'School Position 3', 'workPlaceCatagoryId' => 1],
            ['id' => 4, 'name' => 'School Position 4', 'workPlaceCatagoryId' => 1],
            ['id' => 5, 'name' => 'School Position 5', 'workPlaceCatagoryId' => 1],
            ['id' => 6, 'name' => 'School Position 6', 'workPlaceCatagoryId' => 1],
        
            // Division Positions
            ['id' => 7, 'name' => 'Division Position 1', 'workPlaceCatagoryId' => 2],
            ['id' => 8, 'name' => 'Division Position 2', 'workPlaceCatagoryId' => 2],
        
            // Zone Positions (35 positions)
        ];
        
        $zoneStartId = 9; // Starting ID for zone positions
        for ($i = 1; $i <= 35; $i++) {
            $data[] = ['id' => $zoneStartId++, 'name' => "Zone Position $i", 'workPlaceCatagoryId' => 2];
        }
        
        // Province Positions (35 positions)
        $provinceStartId = $zoneStartId; // Continue from the last zone ID
        for ($i = 1; $i <= 35; $i++) {
            $data[] = ['id' => $provinceStartId++, 'name' => "Province Position $i", 'workPlaceCatagoryId' => 2];
        }
        
        // Ministry Positions (10 positions)
        $ministryStartId = $provinceStartId; // Continue from the last province ID
        for ($i = 1; $i <= 10; $i++) {
            $data[] = ['id' => $ministryStartId++, 'name' => "Ministry Position $i", 'workPlaceCatagoryId' => 3];
        }
        
        // Center Positions (10 positions)
        $centerStartId = $ministryStartId; // Continue from the last ministry ID
        for ($i = 1; $i <= 10; $i++) {
            $data[] = ['id' => $centerStartId++, 'name' => "Center Position $i", 'workPlaceCatagoryId' => 4];
        }

        DB::table('positions')->insert($data);
    }
}
