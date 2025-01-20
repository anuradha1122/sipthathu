<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\School;

class SchoolSearch extends Component
{
    public $search = '';
    public $size;

    public function mount($size)
    {
        $this->size = $size;
    }

    public function render()
    {
        //dd(session()->all());
        $searchResults = '';
        if(strlen($this->search)>0){
            $searchResults = School::join('work_places', 'schools.workPlaceId', '=', 'work_places.id')
            ->join('school_authorities', 'schools.authorityId', '=', 'school_authorities.id')
            ->join('offices AS divisions', 'schools.officeId', '=', 'divisions.id')
            ->join('offices AS zones', 'zones.id', '=', 'divisions.higherOfficeId')
            ->join('work_places AS zoneNames', 'zoneNames.id', '=', 'zones.workPlaceId')
            ->join('offices AS provinces', 'provinces.id', '=', 'zones.higherOfficeId')
            ->join('work_places AS provinceNames', 'provinceNames.id', '=', 'provinces.workPlaceId')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('work_places.name', 'LIKE', '%' . $this->search . '%')
                        ->orWhere('work_places.censusNo', 'LIKE', '%' . $this->search . '%');
                });
            })
            ->where(function($query) {
                $query->where('divisions.id', session('officeId'))
                      ->orWhere('zones.id', session('officeId'))
                      ->orWhere('provinces.id', session('officeId'));
            })            
            ->where('divisions.active', 1)
            ->where('zones.active', 1)
            ->where('provinces.active', 1)
            ->select(
                'schools.id',
                'work_places.censusNo',
                'work_places.name',
                'school_authorities.name AS authority',
                'zoneNames.name AS zone',
                'provinceNames.name AS province', 
                'schools.active'
            )
            ->paginate(8);

        }
        return view('livewire.school-search', ['searchResults' => $searchResults]);
    }

}
