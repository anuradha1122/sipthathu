<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\AppointmentMedium;
use App\Models\User;
use App\Models\UserInService;
use App\Models\UserServiceAppointment;
use App\Models\UserServiceAppointmentPosition;
use App\Models\ContactInfo;
use App\Models\PersonalInfo;
use Illuminate\Support\Facades\Hash;

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
        $subjects = Subject::where('active', 1)->get();
        $appointedMediums = AppointmentMedium::where('active', 1)->get();

        $option = [
            'Dashboard' => 'teacher.dashboard',
            'Teacher Registration' => 'teacher.register'
        ];
        return view('teacher/register',compact('option','subjects','appointedMediums'));
    }

    public function store(StoreUserRequest $request)
    {
        $subjects = Subject::where('active', 1)->get();
        $appointedMediums = AppointmentMedium::where('active', 1)->get();

        $option = [
            'Dashboard' => 'teacher.dashboard',
            'Teacher Registration' => 'teacher.register'
        ];
        //dd($request);
        //$validatedData = $request->validated();
        $name = $request->name;
        // Convert the full name to an array of words
        $nameParts = explode(' ', $name);
        // Convert all parts to title case
        $nameParts = array_map('ucfirst', $nameParts);
        // Get the last name (the last element in the array)
        $lastName = array_pop($nameParts);
        // Generate initials for the rest of the names
        $initials = array_map(function($part) {
            return strtoupper($part[0]) . '.';
        }, $nameParts);
        // Combine initials and last name
        $nameWithInitials = implode('', $initials) . ' ' . $lastName;
    

        $user = User::create([
            'name' => ucwords(strtolower($request->name)),
            'nameWithInitials' => $nameWithInitials,
            'nic' => strtoupper($request->nic),
            'password' => Hash::make(substr($request->nic, 0, 6)),
        ]);

        $userInService = UserInService::create([
            'userId' => $user->id,
            'serviceId' => 1,
            'appointedDate' => $request->serviceDate,
        ]);

        $userServiceAppointment = UserServiceAppointment::create([
            'userServiceId' => $userInService->id,
            'workPlaceId' => $request->school,
            'appointedDate' => $request->serviceDate,
            'appointmentType' => 1,
        ]);

        $contactInfo = ContactInfo::create([
            'userId' => $user->id,
            'permAddressLine1' => ucwords(strtolower($request->addressLine1)),
            'permAddressLine2' => ucwords(strtolower($request->addressLine2)),
            'permAddressLine3' => ucwords(strtolower($request->addressLine3)),
            'mobile1' => $request->mobile,
        ]);
        $contactInfo->save();

        $personalInfo = PersonalInfo::create([
            'userId' => $user->id,
            'genderId' => $request->gender,
            'birthDay' => $request->birthDay,
        ]);
        $personalInfo->save();

        $position = UserServiceAppointmentPosition::create([
            'userServiceAppointmentId' => $userServiceAppointment->id,
            'positionId' => 1,
            'positionedDate' => $request->serviceDate,
        ]);

        session()->flash('success', 'User has been successfully registered!');
        
        return view('teacher/register',compact('option','subjects','appointedMediums'));
    }
}
