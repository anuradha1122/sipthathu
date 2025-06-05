<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\WorkPlace;
use App\Models\School;

class FormProvinceSchool extends Component
{

    public $provinces;
    public $districts;
    public $zones;
    public $schools;
    public $selectedProvince;
    public $selectedDistrict;
    public $selectedZone;
    public $selectedSchool;

    public function __construct()
    {
        $this->districts = collect([]);
        $this->zones = collect([]);
        $this->schools = collect([]);
    }

    public function updatedSelectedProvince($provinceId)
    {
        $this->districts = DB::table('districts')
            ->where('provinceId', $provinceId)
            ->where('active', 1)
            ->get();
    }

    public function updatedSelectedDistrict($districtId)
    {
        $this->zones = DB::table('offices')
            ->join('work_places', 'offices.workPlaceId', '=', 'work_places.id')
            ->where('offices.districtId', $districtId)
            ->where('offices.officeTypeId', 2)
            ->where('work_places.active', 1)
            ->where('offices.active', 1)
            ->select('offices.id as id', 'work_places.name as name')
            ->get();
    }

    public function updatedSelectedZone($zoneId)
    {
        //dd($zoneId);
        $this->schools = DB::table('schools')
            ->join('work_places', 'schools.workPlaceId', '=', 'work_places.id')
            ->join('offices', 'schools.officeId', '=', 'offices.id')
            ->where('offices.higherOfficeId', $zoneId)
            ->where('work_places.active', 1)
            ->where('schools.active', 1)
            ->where('offices.active', 1)
            ->select('schools.id as id', 'work_places.name as name')
            ->get();
            //dd($this->schools);
    }

    public function rules()
    {
        return [
            'province' => 'required',
            'district' => 'required',
            'zone' => 'required',
            'school' => 'required',
        ];
    }

    public function render()
    {
        $this->provinces = DB::table('provinces')
            ->where('active', 1)
            ->get();

        return view('livewire.form-province-school',['provinces' => $this->provinces, 'districts' => $this->districts, 'zones' => $this->zones, 'schools' => $this->schools]);
    }

}
