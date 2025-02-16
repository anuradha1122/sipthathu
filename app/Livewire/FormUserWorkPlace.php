<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\WorkPlace;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class FormUserWorkPlace extends Component
{
    public $workPlaces = [];

    public function updatedSelectedZone($zoneId)
    {
        if (session()->has('ministryId') && session('ministryId') === 1){
            $this->workPlaces = WorkPlace::select('work_places.id', 'work_places.name as name')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            ->where('work_places.active', 1)
            ->where('divisions.active', 1)
            ->where('schools.active', 1)
            ->where('divisions.higherOfficeId', $zoneId)
            ->where('schools.authorityId', 1)
            ->orderBy('work_places.name', 'ASC')
            ->get();
        }else{
            // $this->schools = WorkPlace::select('work_places.id', 'work_places.name as name')
            // ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            // ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            // ->where('work_places.active', 1)
            // ->where('divisions.active', 1)
            // ->where('schools.active', 1)
            // ->where('divisions.higherOfficeId', $zoneId)
            // ->orderBy('work_places.name', 'ASC')
            // ->get();
        }

    }

    public function rules()
    {
        return [
            'zone' => 'required',
            'school' => 'required',
        ];
    }

    public function render()
    {
        //dd(session('officeTypeId'));
        if (session('officeTypeId') == 1) {
            $schools = WorkPlace::select('work_places.id', 'work_places.name as name')
            ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
            ->join('offices AS divisions', 'divisions.id', '=', 'schools.officeId')
            ->join('offices AS zones', 'zones.id', '=', 'divisions.higherOfficeId')
            ->where('work_places.active', 1)
            ->where('divisions.active', 1)
            ->where('schools.active', 1)
            ->where('work_places.categoryId', 1)
            ->where('schools.authorityId', 1)
            ->where('zones.higherOfficeId', session('officeId'))
            ->orderBy('work_places.name', 'ASC');

            $zones = WorkPlace::select(
                'work_places.id',
                'work_places.name',
            )
            ->join('offices AS zones', function ($join) {
                $join->on('zones.workPlaceId', '=', 'work_places.id')
                     ->where('zones.higherOfficeId', session('officeId')); // Fetch zones under session office
            })
            ->where('work_places.active', 1)
            ->where('work_places.categoryId', 2)
            ->orderBy('work_places.name', 'ASC'); // Get zones first

            $divisions = WorkPlace::select(
                'work_places.id',
                'work_places.name',
            )
            ->join('offices AS divisions', function ($join) {
                $join->on('divisions.workPlaceId', '=', 'work_places.id')
                    ->where('divisions.active', 1)
                    ->whereIn('divisions.higherOfficeId', function ($query) {
                        $query->select('id')->from('offices')->where('higherOfficeId', session('officeId'));
                    }); // Fetch only divisions under zones
            })
            ->where('work_places.active', 1)
            ->where('work_places.categoryId', 2)
            ->orderBy('work_places.name', 'ASC'); // Get divisions second


            // Combine all results
            $this->workPlaces = $zones
                ->union($divisions)
                ->union($schools)
                ->orderBy('id', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();

        }

        elseif (session()->has('ministryId') && session('ministryId') === 1){

            $this->workPlaces = WorkPlace::select('work_places.id', 'work_places.name')
                ->join('schools', 'work_places.id', '=', 'schools.workPlaceId')
                ->where('work_places.active', 1)
                ->where('schools.active', 1)
                ->where('work_places.categoryId', 1)
                ->where('schools.authorityId', 1)
                ->orderBy('work_places.name', 'ASC');

            // Adding the static row
            $additionalRow = DB::table(DB::raw("(SELECT 1 AS id, 'Ministry Of Education' AS name) AS temp"));

            // Union the additional row with the existing query
            $this->workPlaces = $additionalRow->union($this->workPlaces)->orderBy('id', 'ASC')->get();
            //dd($workPlaces);
        }

        //dd($this->zones);
        return view('livewire.form-user-work-place', ['workPlaces' => $this->workPlaces]);
    }
}
