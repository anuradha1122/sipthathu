<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransferType;
use App\Models\TransferReason;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class TransferSchool extends Component
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

    public $schoolList;
    public $provinceSchoolList1;
    public $provinceSchoolList2;
    public $provinceSchoolList3;
    public $provinceSchoolList4;
    public $provinceSchoolList5;
    public $provinceList;

    public function mount()
    {
        $this->schoolList = collect([]);
        $this->provinceSchoolList1 = collect([]);
        $this->provinceSchoolList2 = collect([]);
        $this->provinceSchoolList3 = collect([]);
        $this->provinceSchoolList4 = collect([]);
        $this->provinceSchoolList5 = collect([]);

        $this->provinceList = collect([]);
        $this->transferTypes = TransferType::where('active', 1)->get();
        $this->transferReasons = TransferReason::where('active', 1)->get();
    }

    public function updatedSelectedType($typeId)
    {
        $this->typeId = $typeId;

        if($typeId == 1){
            $this->schoolList = DB::table('work_places')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices', 'schools.officeId', '=', 'offices.id')
            ->where('offices.higherOfficeId', session('higherZoneId'))
            ->select('work_places.name', 'schools.id') // or choose specific columns
            ->get();
            //dd($this->schoolList);
        }
        if($typeId == 2){
            $this->schoolList = DB::table('work_places')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices', 'schools.officeId', '=', 'offices.id')
            ->whereIn('offices.districtId', [15, 16])
            ->where('offices.higherOfficeId', '!=', session('higherZoneId'))
            ->select('work_places.name', 'schools.id')
            ->get();
            //dd($this->schoolList);
            //dd($typeId);
        }
        if($typeId == 3){
            $this->provinceList = Province::where('active', 1)->get();
            // $this->schoolList = DB::table('work_places')
            // ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            // ->join('offices', 'schools.officeId', '=', 'offices.id')
            // ->whereNotIn('offices.districtId', [15, 16])
            // ->select('work_places.name', 'schools.id')
            // ->get();
            // dd($this->schoolList);
        }
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
        return view('livewire.transfer-school');
    }
}
