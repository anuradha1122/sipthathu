<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class PrincipalTransferSchool extends Component
{
    public $transferTypes;
    public $transferReasons;
    public $selectedType;
    public $selectedProvince1;
    public $selectedProvince2;
    public $selectedProvince3;
    public $selectedProvince4;
    public $selectedProvince5;
    public $typeId;

    public $provinceSchoolList1;
    public $provinceSchoolList2;
    public $provinceSchoolList3;
    public $provinceSchoolList4;
    public $provinceSchoolList5;
    public $provinceList;

    public function mount()
    {
        $this->provinceList = Province::where('active', 1)->get();
        $this->provinceSchoolList1 = collect([]);
        $this->provinceSchoolList2 = collect([]);
        $this->provinceSchoolList3 = collect([]);
        $this->provinceSchoolList4 = collect([]);
        $this->provinceSchoolList5 = collect([]);
    }

    public function updatedSelectedProvince1($provinceId)
    {
        $this->provinceSchoolList1 = DB::table('work_places')
        ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
        ->join('offices', 'schools.officeId', '=', 'offices.id')
        ->join('districts', 'offices.districtId', '=', 'districts.id')
        ->where('districts.provinceId', $provinceId)
        ->select('work_places.name', 'schools.id')
        ->get();
    }

    public function updatedSelectedProvince2($provinceId)
    {
        $this->provinceSchoolList2 = DB::table('work_places')
        ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
        ->join('offices', 'schools.officeId', '=', 'offices.id')
        ->join('districts', 'offices.districtId', '=', 'districts.id')
        ->where('districts.provinceId', $provinceId)
        ->select('work_places.name', 'schools.id')
        ->get();
    }

    public function updatedSelectedProvince3($provinceId)
    {
        $this->provinceSchoolList3 = DB::table('work_places')
        ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
        ->join('offices', 'schools.officeId', '=', 'offices.id')
        ->join('districts', 'offices.districtId', '=', 'districts.id')
        ->where('districts.provinceId', $provinceId)
        ->select('work_places.name', 'schools.id')
        ->get();
    }

    public function updatedSelectedProvince4($provinceId)
    {
        $this->provinceSchoolList4 = DB::table('work_places')
        ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
        ->join('offices', 'schools.officeId', '=', 'offices.id')
        ->join('districts', 'offices.districtId', '=', 'districts.id')
        ->where('districts.provinceId', $provinceId)
        ->select('work_places.name', 'schools.id')
        ->get();
    }

    public function updatedSelectedProvince5($provinceId)
    {
        $this->provinceSchoolList5 = DB::table('work_places')
        ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
        ->join('offices', 'schools.officeId', '=', 'offices.id')
        ->join('districts', 'offices.districtId', '=', 'districts.id')
        ->where('districts.provinceId', $provinceId)
        ->select('work_places.name', 'schools.id')
        ->get();
    }

    public function render()
    {
        return view('livewire.principal-transfer-school');
    }
}
