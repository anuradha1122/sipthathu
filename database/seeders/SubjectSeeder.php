<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('subjects')->truncate();

        $data = array(
            array("id"=>"1", "name"=>"Primary", "sectionId"=>"1", "categoryId"=>"1", "weekPeriod"=>"1"),
            array("id"=>"2", "name"=>"Primary English", "sectionId"=>"1", "categoryId"=>"1", "weekPeriod"=>"1"),
            array("id"=>"3", "name"=>"Primary Aesthetic", "sectionId"=>"1", "categoryId"=>"1", "weekPeriod"=>"1"),
            array("id"=>"4", "name"=>"Science", "sectionId"=>"2", "categoryId"=>"2", "weekPeriod"=>"1"),
            array("id"=>"5", "name"=>"Mathematics", "sectionId"=>"2", "categoryId"=>"2", "weekPeriod"=>"1"),
            array("id"=>"6", "name"=>"Sinhala", "sectionId"=>"2", "categoryId"=>"2", "weekPeriod"=>"1"),
            array("id"=>"7", "name"=>"Tamil", "sectionId"=>"2", "categoryId"=>"2", "weekPeriod"=>"1"),
            array("id"=>"8", "name"=>"English", "sectionId"=>"2", "categoryId"=>"2", "weekPeriod"=>"1"),
            array("id"=>"9", "name"=>"History", "sectionId"=>"2", "categoryId"=>"2", "weekPeriod"=>"1"),
            array("id"=>"10", "name"=>"Buddhism", "sectionId"=>"2", "categoryId"=>"3", "weekPeriod"=>"1"),
            array("id"=>"11", "name"=>"Hindunism", "sectionId"=>"2", "categoryId"=>"3", "weekPeriod"=>"1"),
            array("id"=>"12", "name"=>"Islam", "sectionId"=>"2", "categoryId"=>"3", "weekPeriod"=>"1"),
            array("id"=>"13", "name"=>"Roman Catholic", "sectionId"=>"2", "categoryId"=>"3", "weekPeriod"=>"1"),
            array("id"=>"14", "name"=>"Cristianity", "sectionId"=>"2", "categoryId"=>"3", "weekPeriod"=>"1"),
            array("id"=>"15", "name"=>"Geography", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"16", "name"=>"Civic Studies", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"17", "name"=>"Business and Accountancy Studies", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"18", "name"=>"Entrepreneurship Studies", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"19", "name"=>"Pali", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"20", "name"=>"Sanskrit", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"21", "name"=>"French", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"22", "name"=>"Hindi", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"23", "name"=>"Japanese", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"24", "name"=>"Arabic", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"25", "name"=>"German", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"26", "name"=>"Chinese", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"27", "name"=>"Russian", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"28", "name"=>"Korean", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"29", "name"=>"2nd language Sinhala", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"30", "name"=>"2nd language Tamil", "sectionId"=>"2", "categoryId"=>"4", "weekPeriod"=>"1"),
            array("id"=>"31", "name"=>"Eastern Music", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"32", "name"=>"Western Music", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"33", "name"=>"Karnatac Music", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"34", "name"=>"Art", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"35", "name"=>"Dancing (Traditional)", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"36", "name"=>"Dancing (Bharat)", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"37", "name"=>"Drama and Theatre", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"38", "name"=>"Appreciation of Literary", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"39", "name"=>"Appreciation of Literary (Arabic)", "sectionId"=>"2", "categoryId"=>"5", "weekPeriod"=>"1"),
            array("id"=>"40", "name"=>"ICT", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"41", "name"=>"Agriculture & Food Technology", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"42", "name"=>"Aquatic Bioresources Technology", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"43", "name"=>"Art and Craft", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"44", "name"=>"Home Economics", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"45", "name"=>"Practical and Technical Skills", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"46", "name"=>"Design and Technology", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"47", "name"=>"Health and Physical Education", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"48", "name"=>"Communication and Media Studies", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"49", "name"=>"Electronic Typing and Shorthand", "sectionId"=>"2", "categoryId"=>"6", "weekPeriod"=>"1"),
            array("id"=>"50", "name"=>"Chemistry", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"51", "name"=>"Physics", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"52", "name"=>"Biology", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"53", "name"=>"Agri Science", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"54", "name"=>"Combined Maths", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"55", "name"=>"Higher Maths", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"56", "name"=>"A/L Maths", "sectionId"=>"3", "categoryId"=>"7", "weekPeriod"=>"1"),
            array("id"=>"57", "name"=>"Engineering Technology", "sectionId"=>"3", "categoryId"=>"8", "weekPeriod"=>"1"),
            array("id"=>"58", "name"=>"Bio System Technology", "sectionId"=>"3", "categoryId"=>"8", "weekPeriod"=>"1"),
            array("id"=>"59", "name"=>"Science for Technology", "sectionId"=>"3", "categoryId"=>"8", "weekPeriod"=>"1"),
            array("id"=>"60", "name"=>"A/L ICT", "sectionId"=>"3", "categoryId"=>"8", "weekPeriod"=>"1"),
            array("id"=>"61", "name"=>"Accountancy", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"62", "name"=>"Business Studies", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"63", "name"=>"Business Statistics", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"64", "name"=>"Logic and Sciencetific Method", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"65", "name"=>"Economics", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"66", "name"=>"A/L Geography", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"67", "name"=>"Political Science", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"68", "name"=>"A/L Home Economics", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"69", "name"=>"History (Indian/Europe/Contemporary)", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"70", "name"=>"Civil Technology", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"71", "name"=>"Machanical Technology", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"72", "name"=>"Electric,Electronic and Information Technology", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"73", "name"=>"Food and Technology", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"74", "name"=>"Agri Technology", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"75", "name"=>"Bio Resources Technology", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"76", "name"=>"A/L Communication and Media Studies", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"77", "name"=>"Buddhist Civilization", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"78", "name"=>"Christian Civilization", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"79", "name"=>"Hindu Civilization", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"80", "name"=>"Islam Civilization", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"81", "name"=>"Greek and Roman Civilization", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"82", "name"=>"A/L Buddhism", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"83", "name"=>"A/L Hindunism", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"84", "name"=>"A/L Christianity", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"85", "name"=>"A/L Islam", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"86", "name"=>"A/L Art", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"87", "name"=>"A/L Dancing (Traditional)", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"88", "name"=>"A/L Dancing (Bharat)", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"89", "name"=>"A/L Eastern Music", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"90", "name"=>"A/L Karnatac Music", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"91", "name"=>"A/L Western Music", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"92", "name"=>"A/L Drama and Theatre", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"93", "name"=>"A/L Sinhala", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"94", "name"=>"A/L Tamil", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"95", "name"=>"A/L English", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"96", "name"=>"A/L Pali", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"97", "name"=>"A/L Sanskrit", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"98", "name"=>"A/L Arabic", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"99", "name"=>"A/L French", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"100", "name"=>"A/L Chinese", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"101", "name"=>"A/L Japanese", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"102", "name"=>"A/L German", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"103", "name"=>"A/L Russian", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"104", "name"=>"A/L Malay", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"105", "name"=>"A/L Hindi", "sectionId"=>"3", "categoryId"=>"9", "weekPeriod"=>"1"),
            array("id"=>"106", "name"=>"Child Psychology & Care", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"107", "name"=>"Health & Social Care", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"108", "name"=>"Physical Education & Sports", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"109", "name"=>"Performing Arts", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"110", "name"=>"Event Management", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"111", "name"=>"A/L Art & Craft", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"112", "name"=>"Interior Designing", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"113", "name"=>"Fashion Designing", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"114", "name"=>"Graphic Design", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"115", "name"=>"Art & Designing", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"116", "name"=>"Landscaping", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"117", "name"=>"Applied Horticulural Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"118", "name"=>"Livestock Product Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"119", "name"=>"Food Processing Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"120", "name"=>"Aquatic Resource  Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"121", "name"=>"Plantation Product Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"122", "name"=>"Construction  Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"123", "name"=>"Automobile Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"124", "name"=>"Electrical and Electronic  Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"125", "name"=>"Textile & Apparel Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"126", "name"=>"Metal Fabrication Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"127", "name"=>"Aluminum Fabrication Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"128", "name"=>"Computer Hardware & Networking (Web Designing and Software Development)", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"129", "name"=>"Manufacturing", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"130", "name"=>"Tourism & Hospitality", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"131", "name"=>"Environmental Studies", "sectionId"=>"4", "categoryId"=>"10", "weekPeriod"=>"1"),
            array("id"=>"132", "name"=>"GIT", "sectionId"=>"5", "categoryId"=>"11", "weekPeriod"=>"1"),
            array("id"=>"133", "name"=>"General English", "sectionId"=>"5", "categoryId"=>"11", "weekPeriod"=>"1")
        );

        DB::table('subjects')->insert($data);
    }
}
