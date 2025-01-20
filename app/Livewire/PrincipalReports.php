<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Race;
use App\Models\Religion;
use App\Models\CivilStatus;
use App\Models\District;
use App\Models\SchoolAuthority;
use App\Models\SchoolEthnicity;
use App\Models\SchoolClass;
use App\Models\SchoolDensity;
use App\Models\SchoolFacility;
use App\Models\SchoolGender;
use App\Models\SchoolLanguage;

class PrincipalReports extends Component
{
    public $province;
    public $district;
    public $zone;
    public $division;
    public $school;
    public $race;
    public $religion;
    public $civilStatus;
    public $gender;
    public $birthDayStart;
    public $birthDayEnd;
    public $serviceStart;
    public $serviceEnd;
    public $schoolAppointStart;
    public $schoolAppointEnd;
    public $schoolAuthority;
    public $schoolEthnicity;
    public $schoolClass;
    public $schoolDensity;
    public $schoolFacility;
    public $schoolGender;
    public $schoolLanguage;

    public $provinceList = [];
    public $districtList;
    public $zoneList;
    public $divisionList;
    public $schoolList;
    public $raceList;
    public $religionList;
    public $civilStatusList;
    public $genderList;
    public $schoolAuthorityList;
    public $schoolEthnicityList;
    public $schoolClassList;
    public $schoolDensityList;
    public $schoolFacilityList;
    public $schoolGenderList;
    public $schoolLanguageList;

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

    public function generateReports(){
        //dd($this->library);
        return $results = DB::table('users')
        ->join('personal_infos', function ($join) {
            $join->on('users.id', '=', 'personal_infos.userId')
                 ->where('personal_infos.active', 1);
        })
        ->leftJoin('civil_statuses', function ($join) {
            $join->on('personal_infos.civilStatusId', '=', 'civil_statuses.id')
                 ->where('civil_statuses.active', 1);
        })
        ->leftJoin('religions', function ($join) {
            $join->on('personal_infos.religionId', '=', 'religions.id')
                 ->where('religions.active', 1);
        })
        ->leftJoin('races', function ($join) {
            $join->on('personal_infos.raceId', '=', 'races.id')
                 ->where('races.active', 1);
        })
        ->leftJoin('user_in_services', function ($join) {
            $join->on('user_in_services.userId', '=', 'users.id')
                 ->where('user_in_services.active', 1)
                 ->where('user_in_services.serviceId', 3);
        })
        ->leftJoin('user_service_appointments', function ($join) {
            $join->on('user_service_appointments.userServiceId', '=', 'user_in_services.id')
                 ->where('user_service_appointments.active', 1);
        })
        ->leftJoin('work_places', function ($join) {
            $join->on('user_service_appointments.workPlaceId', '=', 'work_places.id')
                 ->where('work_places.active', 1);
        })
        ->leftJoin('schools', function ($join) {
            $join->on('schools.workPlaceId', '=', 'work_places.id')
                 ->where('schools.active', 1);
        })
        ->leftJoin('offices AS divisions', function ($join) {
            $join->on('divisions.id', '=', 'schools.officeId')
                 ->where('divisions.active', 1);
        })
        ->leftJoin('work_places AS work_place_divisions', function ($join) {
            $join->on('work_place_divisions.id', '=', 'divisions.workPlaceId')
                 ->where('work_place_divisions.active', 1);
        })
        ->leftJoin('offices AS zones', function ($join) {
            $join->on('zones.id', '=', 'divisions.higherOfficeId')
                 ->where('zones.active', 1);
        })
        ->leftJoin('work_places AS work_place_zones', function ($join) {
            $join->on('work_place_zones.id', '=', 'zones.workPlaceId')
                 ->where('work_place_zones.active', 1);
        })
        ->leftJoin('offices AS provincedeps', function ($join) {
            $join->on('provincedeps.id', '=', 'zones.higherOfficeId')
                 ->where('provincedeps.active', 1);
        })
        ->leftJoin('work_places AS work_place_provinces', function ($join) {
            $join->on('work_place_provinces.id', '=', 'provincedeps.workPlaceId')
                 ->where('work_place_provinces.active', 1);
        })

        ->when($this->race != null && $this->race != 0, function ($query) {
            return $query->where('personal_infos.raceId', $this->race);
        })
        ->when($this->religion != null && $this->religion != 0, function ($query) {
            return $query->where('personal_infos.religionId', $this->religion);
        })
        ->when($this->civilStatus != null && $this->civilStatus != 0, function ($query) {
            return $query->where('personal_infos.civilStatusId', $this->civilStatus);
        })
        ->when($this->gender != null && $this->gender != 0, function ($query) {
            return $query->where('personal_infos.genderId', $this->gender);
        })
        ->when(true, function ($query) {
            $startDate = $this->birthDayStart ?? '1900-01-01';
            $endDate = $this->birthDayEnd ?? Carbon::now()->toDateString();
            return $query->whereBetween('personal_infos.birthDay', [$startDate, $endDate]);
        })
        ->when(true, function ($query) {
            $startDate = $this->serviceStart ?? '1900-01-01';
            $endDate = $this->serviceEnd ?? Carbon::now()->toDateString();
            return $query->whereBetween('user_in_services.appointedDate', [$startDate, $endDate]);
        })
        ->when(true, function ($query) {
            $startDate = $this->schoolAppointStart ?? '1900-01-01';
            $endDate = $this->schoolAppointEnd ?? Carbon::now()->toDateString();
            return $query->whereBetween('user_service_appointments.appointedDate', [$startDate, $endDate]);
        })
        ->when($this->selectedSchool != null && $this->selectedSchool != 0, function ($query) {
            return $query->where('schools.id', $this->selectedSchool);
        })
        ->when($this->selectedDivision != null && $this->selectedDivision != 0, function ($query) {
            return $query->where('divisions.id', $this->selectedDivision);
        })
        ->when($this->selectedZone != null && $this->selectedZone != 0, function ($query) {
            return $query->where('zones.id', $this->selectedZone);
        })
        ->when($this->selectedDistrict != null && $this->selectedDistrict != 0, function ($query) {
            return $query->where('zones.districtId', $this->selectedDistrict);
        })
        ->when($this->selectedProvince != null && $this->selectedProvince != 0, function ($query) {
            return $query->where('zones.higherOfficeId', $this->selectedProvince);
        })
        ->when($this->schoolAuthority != null && $this->schoolAuthority != 0, function ($query) {
            return $query->where('schools.authorityId', $this->schoolAuthority);
        })
        ->when($this->schoolEthnicity != null && $this->schoolEthnicity != 0, function ($query) {
            return $query->where('schools.ethnicityId', $this->schoolEthnicity);
        })
        ->when($this->schoolClass != null && $this->schoolClass != 0, function ($query) {
            return $query->where('schools.classId', $this->schoolClass);
        })
        ->when($this->schoolDensity != null && $this->schoolDensity != 0, function ($query) {
            return $query->where('schools.densityId', $this->schoolDensity);
        })
        ->when($this->schoolFacility != null && $this->schoolFacility != 0, function ($query) {
            return $query->where('schools.facilityId', $this->schoolFacility);
        })
        ->when($this->schoolGender != null && $this->schoolGender != 0, function ($query) {
            return $query->where('schools.genderId', $this->schoolGender);
        })
        ->when($this->schoolLanguage != null && $this->schoolLanguage != 0, function ($query) {
            return $query->where('schools.languageId', $this->schoolLanguage);
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
        ->select(
            'users.name AS userName',
            'users.nameWithInitials',
            'users.nic',
            'users.email',
            'races.name AS race',
            'religions.name AS religion',
            'civil_statuses.name AS civilStatus',
            DB::raw("CASE
                WHEN personal_infos.genderId = 1 THEN 'Male'
                WHEN personal_infos.genderId = 2 THEN 'Female'
                ELSE 'Other'
             END AS gender"),
            'personal_infos.birthDay',
            'work_places.name AS workPlaceName',
            'work_place_divisions.name AS divisionName',
            'work_place_zones.name AS zoneName'
        )
        ->orderBy('personal_infos.birthDay', 'DESC')
        ->orderBy('users.name', 'DESC')
        ->paginate(10);

    }

    public function render()
    {
        $this->raceList = Race::where('active', 1)->get();
        $this->religionList = Religion::where('active', 1)->get();
        $this->civilStatusList = CivilStatus::where('active', 1)->get();
        $this->genderList = collect([
            (object) ['id' => 1, 'name' => 'Male'],
            (object) ['id' => 2, 'name' => 'Female'],
        ]);
        $this->schoolAuthorityList = SchoolAuthority::where('active', 1)->get();
        $this->schoolEthnicityList = SchoolEthnicity::where('active', 1)->get();
        $this->schoolClassList = SchoolClass::where('active', 1)->get();
        $this->schoolDensityList = SchoolDensity::where('active', 1)->get();
        $this->schoolFacilityList = SchoolFacility::where('active', 1)->get();
        $this->schoolGenderList = SchoolGender::where('active', 1)->get();
        $this->schoolLanguageList = SchoolLanguage::where('active', 1)->get();

        $results = $this->generateReports();
        return view('livewire.principal-reports', ['results' => $results]);
    }
}
