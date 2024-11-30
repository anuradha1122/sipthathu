<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Race;
use App\Models\Religion;
use App\Models\CivilStatus;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chartData = [
            ['Book Catagory', 'Amount'],
            ["Novels", 44],
            ["Short Story", 31],
            ["Documantary", 12],
            ["Children's Boos", 10],
            ['Other', 3]
        ];
        $option = ['Dashboard' => 'teacher.dashboard'];
        
        $card_pack_1 = collect([
            (object) [
                'id' => 1,
                'name' => 'Teacher',
                'user_count' => 25,
            ],
            (object) [
                'id' => 2,
                'name' => 'Principal',
                'user_count' => 5,
            ],
            (object) [
                'id' => 3,
                'name' => 'SLEAS',
                'user_count' => 10,
            ],
            (object) [
                'id' => 4,
                'name' => 'SLAS',
                'user_count' => 8,
            ],
            (object) [
                'id' => 5,
                'name' => 'Development Officer',
                'user_count' => 12,
            ],
        ]);
        //dd($card_pack_1);
        return view('teacher/dashboard',compact('option','card_pack_1','chartData'));
    }

    public function create()
    {
        $races = Race::where('active', 1)->get();
        $religions = Religion::where('active', 1)->get();
        $civilStatuses = CivilStatus::where('active', 1)->get();
        $option = [
            'Dashboard' => 'teacher.dashboard',
            'Teacher Registration' => 'teacher.register'
        ];
        return view('teacher/register',compact('option','races','religions','civilStatuses'));
    }
}
