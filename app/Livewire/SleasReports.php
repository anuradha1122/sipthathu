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

class SleasReports extends Component
{
    public $workPlace;
    public $race;
    public $religion;
    public $civilStatus;
    public $gender;
    public $birthDayStart;
    public $birthDayEnd;
    public $serviceStart;
    public $serviceEnd;

    public $workPlaceList;
    public $raceList;
    public $religionList;
    public $civilStatusList;
    public $genderList;

    public $selectedWorkPlace;
    //public $results =[];

    public function mount()
    {
        if(session('ministryId') && session('ministryId') == 1)
        {

            $schools = DB::table('work_places')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->where('work_places.active', 1)
            ->where('schools.active', 1)
            ->where('work_places.categoryId', 1)
            ->where('schools.authorityId', 1)
            ->select('work_places.id', 'work_places.name as name');


            $offices = DB::table('work_places')
                ->join('offices', 'offices.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('offices.active', 1)
                ->select('work_places.id', 'work_places.name'); // Select specific columns

            // Adding the static row
            $ministry = DB::table(DB::raw("(SELECT 1 AS id, 'Ministry Of Education' AS name) AS temp"));

            // Union the additional row with the existing query
            //$this->workPlaces = $additionalRow->union($this->workPlaces)->orderBy('id', 'ASC')->get();

            $this->workPlaceList = $schools
            ->union($offices)
            ->union($ministry)
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();

        }elseif (session('officeId') && session('officeTypeId') == 1) {
            $schools = DB::table('work_places')
                ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
                ->join('offices AS division', 'division.id', '=', 'schools.officeId')
                ->join('offices AS zones', 'zones.id', '=', 'division.higherOfficeId')
                ->where('work_places.active', 1)
                ->where('schools.active', 1)
                ->where('work_places.categoryId', 1)
                ->where('schools.authorityId', 1)
                ->where('zones.higherOfficeId', session('officeId'))
                ->select('work_places.id', 'work_places.name as name');


            $divisions = DB::table('work_places')
                ->join('offices AS divisions', 'divisions.workPlaceId', '=', 'work_places.id')
                ->join('offices AS zones' , 'zones.id', '=', 'divisions.higherOfficeId')
                ->where('work_places.active', 1)
                ->where('zones.higherOfficeId', session('officeId'))
                ->select('work_places.id', 'work_places.name'); // Select specific columns

            $zones = DB::table('work_places')
                ->join('offices AS zones', 'zones.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('zones.higherOfficeId', session('officeId'))
                ->select('work_places.id', 'work_places.name'); // Select specific columns

            $province = DB::table('work_places')
                ->join('offices AS provinces', 'provinces.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('provinces.id', session('officeId'))
                ->select('work_places.id', 'work_places.name'); // Select specific columns

            $this->workPlaceList = $schools
            ->union($divisions)
            ->union($zones)
            ->union($province)
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();

        }elseif (session('officeId') && session('officeTypeId') == 2) {
            $schools = DB::table('work_places')
                ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
                ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
                ->where('work_places.active', 1)
                ->where('schools.active', 1)
                ->where('work_places.categoryId', 1)
                ->where('schools.authorityId', 1)
                ->where('divisions.higherOfficeId', session('officeId'))
                ->select('work_places.id', 'work_places.name as name');

            $divisions = DB::table('work_places')
                ->join('offices AS divisions', 'divisions.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('divisions.higherOfficeId', session('officeId'))
                ->select('work_places.id', 'work_places.name'); // Select specific columns

            $zone = DB::table('work_places')
                ->join('offices AS zones', 'zones.workPlaceId', '=', 'work_places.id')
                ->where('work_places.active', 1)
                ->where('zones.id', session('officeId'))
                ->select('work_places.id', 'work_places.name'); // Select specific columns

            $this->workPlaceList = $schools
            ->union($divisions)
            ->union($zone)
            ->orderBy('id', 'ASC')
            ->orderBy('name', 'ASC')
            ->get();

        }
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
                 ->where('user_in_services.serviceId', 4);
        })
        ->leftJoin('user_service_appointments', function ($join) {
            $join->on('user_service_appointments.userServiceId', '=', 'user_in_services.id')
                 ->where('user_service_appointments.active', 1)
                 ->where('user_service_appointments.current', 1);
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
        ->when($this->selectedWorkPlace != null && $this->selectedWorkPlace != 0, function ($query) {
            return $query->where('work_places.id', $this->selectedWorkPlace);
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

        $results = $this->generateReports();
        return view('livewire.sleas-reports', ['results' => $results]);
    }
}
