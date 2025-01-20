<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Race;
use App\Models\Religion;
use App\Models\BloodGroup;
use App\Models\Grade;
use App\Models\Province;
use App\Models\District;
use App\Models\Office;
use App\Models\School;

class StudentReports extends Component
{
    public $province;
    public $district;
    public $zone;
    public $division;
    public $school;
    public $race;
    public $religion;
    public $bloodGroup;
    public $gender;
    public $grade;
    public $birthDayStart;
    public $birthDayEnd;

    public $provinceList = [];
    public $districtList;
    public $zoneList;
    public $divisionList;
    public $schoolList;

    public $raceList;
    public $religionList;
    public $bloodGroupList;
    public $genderList;
    public $gradeList;

    public $selectedProvince;
    public $selectedDistrict;
    public $selectedZone;
    public $selectedDivision;
    public $selectedSchool;

    public function mount()
    {
        if(session('ministryId') && session('ministryId') == 1)
        {
            $this->provinceList = DB::table('provinces')
                ->where('active', 1)
                ->get();
            $this->districtList = collect([]);
            $this->zoneList = collect([]);
            $this->divisionList = collect([]);
            $this->schoolList = collect([]);
        }elseif (session('officeId') && session('officeTypeId') == 1) {
            $this->districtList = DB::table('districts')
                ->join('offices', 'offices.districtId', '=', 'districts.id')
                ->where('districts.active', 1)
                ->where('offices.active', 1)
                ->where('offices.higherOfficeId', session('officeId'))
                ->select('districts.id', 'districts.name') // Select specific columns
                ->distinct() // Ensure distinct results
                ->get();
            $this->zoneList = collect([]);
            $this->divisionList = collect([]);
            $this->schoolList = collect([]);
            //dd($this->districtList);
        }elseif (session('officeId') && session('officeTypeId') == 2) {
            $this->divisionList = DB::table('offices')
                ->join('work_places', 'offices.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('offices.active', 1)
                ->where('offices.higherOfficeId', session('officeId'))
                ->select('offices.id', 'work_places.name') // Select specific columns
                ->get();
            $this->schoolList = collect([]);
        }elseif (session('officeId') && session('officeTypeId') == 3) {
            $this->schoolList = DB::table('schools')
                ->join('work_places', 'schools.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('schools.active', 1)
                ->where('schools.officeId', session('officeId'))
                ->select('schools.id', 'work_places.name') // Select specific columns
                ->get();
        }
    }

    public function updatedSelectedProvince($provinceId)
    {
        $this->districtList = District::where('provinceId', $provinceId)
                                   ->where('active', 1)
                                   ->get();
        $this->selectedDistrict = null;
        $this->selectedZone = null;
        $this->selectedDivision = null;
        $this->selectedSchool = null;
    }

    public function updatedSelectedDistrict($districtId)
    {
        $this->zoneList = DB::table('offices')
                    ->join('work_places', 'work_places.id', '=', 'offices.workPlaceId')
                    ->select('offices.id', 'work_places.name')
                    ->where('offices.districtId', $districtId)
                    ->where('offices.officeTypeId', 2)
                    ->where('offices.active', 1)
                    ->get();
        $this->selectedZone = null;
        $this->selectedDivision = null;
        $this->selectedSchool = null;
        //dd($this->zoneList);
    }

    public function updatedSelectedZone($zoneId)
    {
        //dd($this->zoneList);
        $this->divisionList = DB::table('offices')
                        ->join('work_places', 'work_places.id', '=', 'offices.workPlaceId')
                        ->select('offices.id', 'work_places.name')
                        ->where('offices.higherOfficeId', $zoneId)
                        ->where('offices.officeTypeId', 3)
                        ->where('offices.active', 1)
                        ->get();
        $this->selectedDivision = null;
        $this->selectedSchool = null;

    }

    public function updatedSelectedDivision($divisionId)
    {
        $this->schoolList = DB::table('schools')
                            ->join('work_places', 'work_places.id', '=', 'schools.workPlaceId')
                            ->select('schools.id', 'work_places.name')
                            ->where('schools.officeId', $divisionId)
                            ->where('schools.active', 1)
                            ->get();
        $this->selectedSchool = null;

    }


    public function generateReports() {
        $results = DB::table('students')
            ->leftjoin('student_personal_infos', 'student_personal_infos.studentId', '=', 'students.id')
            ->leftjoin('student_contact_infos', 'student_contact_infos.studentId', '=', 'students.id')
            ->leftjoin('student_location_infos', 'student_location_infos.studentId', '=', 'students.id')
            ->leftjoin('student_schools', 'student_schools.studentId', '=', 'students.id')
            ->leftjoin('school_class_students', 'school_class_students.studentId', '=', 'students.id')
            ->leftjoin('races', 'races.id', '=', 'student_personal_infos.raceId')
            ->leftjoin('religions', 'religions.id', '=', 'student_personal_infos.religionId')
            ->leftjoin('blood_groups', 'blood_groups.id', '=', 'student_personal_infos.bloodGroupId')
            ->leftjoin('guardian_relationships', 'guardian_relationships.id', '=', 'student_contact_infos.guardianRelationshipId')
            ->leftjoin('offices', 'offices.id', '=', 'student_location_infos.educationDivisionId')
            ->leftjoin('work_places AS work_place_edudivisions', 'work_place_edudivisions.id', '=', 'offices.workPlaceId')
            ->leftjoin('gn_divisions', 'gn_divisions.id', '=', 'student_location_infos.gnDivisionId')
            ->leftjoin('ds_divisions', 'ds_divisions.id', '=', 'gn_divisions.dsId')
            ->leftjoin('districts', 'districts.id', '=', 'ds_divisions.districtId')
            ->leftjoin('provinces', 'provinces.id', '=', 'districts.provinceId')
            ->leftjoin('schools', 'schools.id', '=', 'student_schools.schoolId')
            ->leftjoin('work_places AS work_place_schools', 'work_place_schools.id', '=', 'schools.workPlaceId')
            ->leftjoin('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            ->leftjoin('work_places AS work_place_divisions', 'work_place_divisions.id', '=', 'divisions.workPlaceId')
            ->leftjoin('offices AS zones', 'zones.id', '=', 'divisions.higherOfficeId')
            ->leftjoin('work_places AS work_place_zones', 'work_place_zones.id', '=', 'zones.workPlaceId')
            ->leftjoin('offices AS provincedeps', 'provincedeps.id', '=', 'zones.higherOfficeId')
            ->leftjoin('work_places AS work_place_provinces', 'work_place_provinces.id', '=', 'provincedeps.workPlaceId')
            ->leftjoin('school_class_lists', 'school_class_lists.id', '=', 'school_class_students.schoolClassId')
            ->leftjoin('class_lists', 'class_lists.id', '=', 'school_class_lists.classId')
            ->select(
                'students.*',
                'provincedeps.id AS provincedepId',
                'student_personal_infos.*',
                'student_contact_infos.*',
                'student_location_infos.*',
                'guardian_relationships.name AS guardianRelationship',
                'races.name as race',
                'religions.name as religion',
                'blood_groups.name as bloodGroup',
                DB::raw("CASE WHEN student_personal_infos.genderId = 1 THEN 'Boy' WHEN student_personal_infos.genderId = 2 THEN 'Girl' END as gender"),
                'work_place_divisions.name as educationDivision',
                'gn_divisions.name as gnDivision',
                'ds_divisions.name as dsDivision',
                'districts.name as district',
                'provinces.name as province',
                'work_place_schools.name as school',
                'class_lists.name as class'
            )
            ->when($this->race != null && $this->race != 0, function ($query) {
                return $query->where('student_personal_infos.raceId', $this->race);
            })
            ->when($this->religion != null && $this->religion != 0, function ($query) {
                return $query->where('student_personal_infos.religionId', $this->religion);
            })
            ->when($this->bloodGroup != null && $this->bloodGroup != 0, function ($query) {
                return $query->where('student_personal_infos.bloodGroupId', $this->bloodGroup);
            })
            ->when($this->gender != null && $this->gender != 0, function ($query) {
                return $query->where('student_personal_infos.genderId', $this->gender);
            })
            ->when($this->grade != null && $this->grade != 0, function ($query) {
                return $query->where('class_lists.gradeId', $this->grade);
            })
            ->when($this->selectedSchool != null && $this->selectedSchool != 0, function ($query) {
                return $query->where('student_schools.schoolId', $this->selectedSchool);
            })
            ->when($this->selectedDivision != null && $this->selectedDivision != 0, function ($query) {
                return $query->where('schools.officeId', $this->selectedDivision);
            })
            ->when($this->selectedZone != null && $this->selectedZone != 0, function ($query) {
                return $query->where('divisions.higherOfficeId', $this->selectedZone);
            })
            ->when($this->selectedDistrict != null && $this->selectedDistrict != 0, function ($query) {
                return $query->where('zones.DistrictId', $this->selectedDistrict);
            })
            ->when(true, function ($query) {
                $startDate = $this->birthDayStart ?? '1900-01-01';
                $endDate = $this->birthDayEnd ?? Carbon::now()->toDateString();
                return $query->whereBetween('student_personal_infos.birthDay', [$startDate, $endDate]);
            })
            ->when(session('schoolId'), function ($query) {
                return $query->where('schools.id', session('schoolId'));
            })
            ->when(session('officeId') && session('officeTypeId') == 3, function ($query) {
                return $query->where('divisions.id', session('officeId'));
            })
            ->when(session('officeId') && session('officeTypeId') == 2, function ($query) {
                return $query->where('zones.id', session('officeId'));
            })
            ->when(session('officeId') && session('officeTypeId') == 1, function ($query) {
                return $query->where('provincedeps.id', session('officeId'));
            })
            ->where('students.active', 1)
            ->where('student_personal_infos.active', 1)
            ->where('student_contact_infos.active', 1)
            ->where('student_location_infos.active', 1)
            ->where('student_schools.active', 1)
            ->where('school_class_students.active', 1)
            ->where('school_class_students.current', 1)
            ->where('student_schools.current', 1)
            ->paginate(10);

        return $results;
    }


    public function render()
    {
        $this->raceList = Race::where('active', 1)->get();
        $this->religionList = Religion::where('active', 1)->get();
        $this->bloodGroupList = BloodGroup::where('active', 1)->get();
        $this->genderList = collect([
            (object) [
                'id' => 1,
                'name' => 'Male',
            ],
            (object) [
                'id' => 2,
                'name' => 'Female',
            ],
        ]);
        $this->gradeList = Grade::where('active', 1)->get();

        $results = $this->generateReports();

        return view('livewire.student-reports', ['results' => $results]);
    }
}
