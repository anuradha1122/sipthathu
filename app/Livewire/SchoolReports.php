<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\SchoolAuthority;
use App\Models\SchoolClass;
use App\Models\SchoolDensity;
use App\Models\SchoolEthnicity;
use App\Models\SchoolFacility;
use App\Models\SchoolGender;
use App\Models\SchoolLanguage;
use App\Models\SchoolReligion;

class SchoolReports extends Component
{
    use WithPagination;
    public $name;
    public $authority;
    public $class;
    public $density;
    public $ethnicity;
    public $facility;
    public $gender;
    public $language;
    public $religion;

    public $authorityList;
    public $classList;
    public $densityList;
    public $ethnicityList;
    public $facilityList;
    public $genderList;
    public $languageList;
    public $religionList;

    public function generateReports(){

        return $results = DB::table('schools')
        ->join('work_places', 'schools.workPlaceId', '=', 'work_places.id')
        ->join('school_authorities', 'schools.authorityId', '=', 'school_authorities.id')
        ->join('school_classes', 'schools.classId', '=', 'school_classes.id')
        ->join('school_densities', 'schools.densityId', '=', 'school_densities.id')
        ->join('school_ethnicities', 'schools.ethnicityId', '=', 'school_ethnicities.id')
        ->join('school_facilities', 'schools.facilityId', '=', 'school_facilities.id')
        ->join('school_genders', 'schools.genderId', '=', 'school_genders.id')
        ->join('school_languages', 'schools.languageId', '=', 'school_languages.id')
        ->join('school_religions', 'schools.religionId', '=', 'school_religions.id')
        
        ->when($this->authority != null && $this->authority != 0, function ($query) {
            return $query->where('schools.authorityId', $this->authority);
        })
        ->when($this->class != null && $this->class != 0, function ($query) {
            return $query->where('schools.classId', $this->class);
        })
        ->when($this->density != null && $this->density != 0, function ($query) {
            return $query->where('schools.densityId', $this->density);
        })
        ->when($this->ethnicity != null && $this->ethnicity != 0, function ($query) {
            return $query->where('schools.ethnicityId', $this->ethnicity);
        })
        ->when($this->facility != null && $this->facility != 0, function ($query)   {
            return $query->where('schools.facilityId', $this->facility);
        })
        ->when($this->gender != null && $this->gender != 0, function ($query)   {
            return $query->where('schools.genderId', $this->gender);
        })
        ->when($this->language != null && $this->language != 0, function ($query)   {
            return $query->where('schools.languageId', $this->language);
        })
        ->when($this->religion != null && $this->religion != 0, function ($query)   {
            return $query->where('schools.religionId', $this->religion);
        })
        
        ->select(
            'work_places.name AS schoolName',
            'school_authorities.name AS authority',
            'school_classes.name AS class',
            'school_densities.name AS density',
            'school_ethnicities.name AS ethnicity',
            'school_facilities.name AS facility',
            'school_genders.name AS gender',
            'school_languages.name AS language',
            'school_religions.name AS religion',
        )
        ->orderBy('work_places.name', 'DESC')
        ->paginate(10);
    
        
    }

    public function render()
    {
        $this->authorityList = SchoolAuthority::where('active', 1)->get();
        $this->classList = SchoolClass::where('active', 1)->get();
        $this->densityList = SchoolDensity::where('active', 1)->get();
        $this->ethnicityList = SchoolEthnicity::where('active', 1)->get();
        $this->facilityList = SchoolFacility::where('active', 1)->get();
        $this->genderList = SchoolGender::where('active', 1)->get();
        $this->languageList = SchoolLanguage::where('active', 1)->get();
        $this->religionList = SchoolReligion::where('active', 1)->get();

        $results = $this->generateReports();
        return view('livewire.school-reports', ['results' => $results]);
    }
}
