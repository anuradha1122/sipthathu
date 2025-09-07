<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\TransferType;

class PrincipalTransferSummary extends Component
{
    use WithPagination;

    public $province;
    public $district;
    public $zone;
    public $division;
    public $school;

    public $provinceList = [];
    public $districtList;
    public $zoneList;
    public $divisionList;
    public $schoolList;
    public $typeList;
    public $recommendationList;

    public $selectedProvince;
    public $selectedDistrict;
    public $selectedZone;
    public $selectedDivision;
    public $selectedSchool;
    public $selectedType;
    public $selectedReco;

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

        ->join('principal_transfers', function ($join) {
            $join->on('principal_transfers.userServiceId', '=', 'user_in_services.id')
                 ->where('principal_transfers.active', 1);
        })

        ->join('user_service_appointments', function ($join) {
            $join->on('user_service_appointments.userServiceId', '=', 'user_in_services.id')
                 ->where('user_service_appointments.active', 1)
                 ->where('user_service_appointments.current', 1)
                 ->where('user_service_appointments.releasedDate', NULL);
        })
        ->join('work_places', function ($join) {
            $join->on('user_service_appointments.workPlaceId', '=', 'work_places.id')
                 ->where('work_places.active', 1);
        })
        ->join('schools', function ($join) {
            $join->on('schools.workPlaceId', '=', 'work_places.id')
                 ->where('schools.active', 1)
                 ->where('schools.authorityId', 2);
        })
        ->join('offices AS divisions', function ($join) {
            $join->on('divisions.id', '=', 'schools.officeId')
                 ->where('divisions.active', 1);
        })
        ->join('work_places AS work_place_divisions', function ($join) {
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
        ->when(in_array($this->selectedReco, [1, 2]), function ($query) {
            if ($this->selectedReco == 1) {
                return $query->whereNotNull('principal_transfers.zonalId');
            }
            if ($this->selectedReco == 2) {
                return $query->whereNull('principal_transfers.zonalId');
            }
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
            'principal_transfers.refferenceNo',
            'principal_transfers.zonalId',
            'work_places.name AS workPlaceName',
            'work_place_divisions.name AS divisionName',
            'work_place_zones.name AS zoneName'
        )
        ->orderBy('work_places.name', 'DESC')
        ->paginate(10);

    }

    public function render()
    {
        $this->recommendationList = collect([
            (object) ['id' => 1, 'name' => 'Yes'],
            (object) ['id' => 2, 'name' => 'No'],
        ]);
        $results = $this->generateReports();
        return view('livewire.principal-transfer-summary', ['results' => $results]);
    }
}
