<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RaceSeeder::class);
        $this->call(ReligionSeeder::class);
        $this->call(CivilStatusSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(RankSeeder::class);
        $this->call(SchoolAuthoritySeeder::class);
        $this->call(SchoolClassSeeder::class);
        $this->call(SchoolDensitySeeder::class);
        $this->call(SchoolEthnicitySeeder::class);
        $this->call(SchoolFacilitySeeder::class);
        $this->call(SchoolGenderSeeder::class);
        $this->call(SchoolLanguageSeeder::class);
        $this->call(SchoolReligionSeeder::class);
        $this->call(CenterTypeSeeder::class);
        $this->call(DashboardSeeder::class);
        $this->call(FamilyMemberTypeSeeder::class);
        $this->call(MinistryTypeSeeder::class);
        $this->call(OfficeTypeSeeder::class);
        $this->call(WorkPlaceCatagorySeeder::class);
        $this->call(positionSeeder::class);
    }
}
