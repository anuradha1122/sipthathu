<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TransferType;
use App\Models\TransferReason;
use Illuminate\Support\Facades\DB;

class TransferSchool extends Component
{
    public $transferTypes;
    public $transferReasons;
    public $selectedType;
    public $typeId;

    public $schoolList;

    public function mount()
    {
        $this->schoolList = collect([]);
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
            $this->schoolList = DB::table('work_places')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices', 'schools.officeId', '=', 'offices.id')
            ->whereNotIn('offices.districtId', [15, 16])
            ->select('work_places.name', 'schools.id')
            ->get();
            //dd($this->schoolList);
        }
    }

    public function render()
    {
        return view('livewire.transfer-school');
    }
}
