<?php

namespace App\Http\Controllers;

use App\Providers\RouteServiceProvider;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function attachment(Request $request)
    {
        if(session('isAttached')==0){
            $userServiceAppointment = DB::table('user_service_appointments')
            ->where('userServiceId', session('userServiceId'))
            ->where('appointmentType', 2)
            ->where('releasedDate', NULL)
            ->where('active', 1)
            ->first(['id', 'workPlaceId']);

            session(['isAttached' => 1]);

        }else{
            $userServiceAppointment = DB::table('user_service_appointments')
            ->where('userServiceId', session('userServiceId'))
            ->where('appointmentType', 1)
            ->where('releasedDate', NULL)
            ->where('active', 1)
            ->first(['id', 'workPlaceId']);

            session(['isAttached' => 0]);

        }

        $userServiceAppointmentId = $userServiceAppointment->id ?? null; // Handle null if no match
        $workPlaceId = $userServiceAppointment->workPlaceId ?? null;

        $workPlace = DB::table('work_places')
        ->where('id', $workPlaceId)
        ->where('active', 1)
        ->first(['name', 'censusNo', 'categoryId']);

        $workPlaceName = $workPlace->name ?? null; // Handle null if no match
        $workPlaceCensusNo = $workPlace->censusNo ?? null;
        $workPlaceCategoryId = $workPlace->categoryId ?? null;

        $positionId = DB::table('user_service_appointment_positions')
        ->where('userServiceAppointmentId', $userServiceAppointmentId)
        ->where('current', 1)
        ->where('active', 1)
        ->value('positionId');

        session(['appointmentId' => $userServiceAppointmentId]);
        session(['workPlaceId' => $workPlaceId]);
        session(['workPlaceName' => $workPlaceName]);
        session(['workPlaceCensusNo' => $workPlaceCensusNo]);
        session(['workPlaceCategoryId' => $workPlaceCategoryId]);
        //session(['attachmentIds' => $userServiceAppointmentIds]);
        session(['positionId' => $positionId]);

        if($workPlaceCategoryId ==1){
            $school = DB::table('schools')
            ->where('workPlaceId', $workPlaceId)
            ->where('active', 1)
            ->first(['officeId', 'id']);

            $schoolId = $school->id ?? null; // Handle null if no match
            $higherDivId = $school->officeId ?? null;

            $zone = DB::table('offices')
            ->where('id', $higherDivId)
            ->where('active', 1)
            ->first(['higherOfficeId']);

            $higherZoneId = $zone->higherOfficeId ?? null;

            $province = DB::table('offices')
            ->where('id', $higherZoneId)
            ->where('active', 1)
            ->first(['higherOfficeId']);

            $higherProviId = $province->higherOfficeId ?? null;

            session(['schoolId' => $schoolId]);
            session(['higherDivId' => $higherDivId]);
            session(['higherZoneId' => $higherZoneId]);
            session(['higherProviId' => $higherProviId]);
            //dd(session('higherZoneId'));


        }
        if($workPlaceCategoryId ==2){
            $office = DB::table('offices')
            ->where('workPlaceId', $workPlaceId)
            ->where('active', 1)
            ->first(['officeTypeId', 'id', 'higherOfficeId']);

            $officeId = $office->id ?? null; // Handle null if no match
            $officeType = $office->officeTypeId ?? null;
            $higherOfficeId = $office->higherOfficeId ?? null;

            if($officeType == 3){
                $higherZoneId = $higherOfficeId;
                $office = DB::table('offices')
                ->where('id', $higherZoneId)
                ->where('active', 1)
                ->first(['higherOfficeId']);
                $higherProviId = $office->higherOfficeId ?? null;

                session(['officeId' => $officeId]);
                session(['officeTypeId' => $officeType]);
                session(['higherZoneId' => $higherZoneId]);
                session(['higherProviId' => $higherProviId]);
            }
            if($officeType == 2){
                $higherProviId = $higherOfficeId;

                session(['officeId' => $officeId]);
                session(['officeTypeId' => $officeType]);
                session(['higherProviId' => $higherProviId]);
            }

            if($officeType == 1){

                session(['officeId' => $officeId]);
                session(['officeTypeId' => $officeType]);
            }

        }
        if($workPlaceCategoryId ==3){
            $ministry = DB::table('ministries')
            ->where('workPlaceId', $workPlaceId)
            ->where('active', 1)
            ->first([ 'id', 'officeId']);

            $ministryId = $ministry->id ?? null; // Handle null if no match
            $relateOfficeId = $ministry->officeId ?? null;

            Auth::user()->setAttribute('ministryId', $ministryId);
            Auth::user()->setAttribute('relateOfficeId', $relateOfficeId);

            session(['ministryId' => $ministryId]);
            session(['relateOfficeId' => $relateOfficeId]);
        }

        //dd(session()->all());
        return redirect()->intended(RouteServiceProvider::HOME);

    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function passwordreset(Request $request)
    {
        $nic = $request->query('nic');

        if (!$nic) {
            return redirect()->route('welcome'); // or: return view('welcome');
        }

        return view('passwordreset', compact('nic'));
    }

    public function updatePassword(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'nic' => ['required', 'regex:/^([0-9]{9}[VvXx]|[0-9]{12})$/'],
            'newpassword' => 'required|min:6',
            'confirmpassword' => 'required|same:newpassword',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Determine NIC — from session or hidden input or query
        $nic = $request->nic;
        //dd($nic);
        if (!$nic) {
            return redirect()->route('welcome');
        }
        //dd($nic);
        $user = \App\Models\User::where('nic', $nic)->first();

        if (!$user) {
            return redirect()->route('welcome')->withErrors(['nic' => 'User not found']);
        }
        //dd($request->newpassword);
        // Update password
        $user->password = Hash::make($request->newpassword);
        $user->save();

        // Clear session value (optional)
        session()->forget('force_password_reset_nic');

        return redirect()->route('login')->with('status', 'Password changed successfully. Please log in.');
    }
}
