<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransferTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('transfer_types')->truncate();

        $data = array(
            array('id' => '1','name' => 'within zone'),
            array('id' => '2','name' => 'another zone'),
            array('id' => '3','name' => 'another province'),
            array('id' => '4','name' => 'national school')
          );

        DB::table('transfer_types')->insert($data);
    }
}
