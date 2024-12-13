<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $userServiceId = DB::table('user_in_services')
        ->where('userId', Auth::id())
        ->where('current', 1)
        ->where('active', 1)
        ->value('id');

        $userServiceAppointment = DB::table('user_service_appointments')
        ->where('userServiceId', $userServiceId)
        ->where('appointmentType', 1)
        ->where('current', 1)
        ->where('active', 1)
        ->first(['id', 'workPlaceId']);

        $userServiceAppointmentId = $userServiceAppointment->id ?? null; // Handle null if no match
        $workPlaceId = $userServiceAppointment->workPlaceId ?? null;

        $workPlace = DB::table('work_places')
        ->where('id', $workPlaceId)
        ->where('active', 1)
        ->first(['name', 'censusNo', 'categoryId']);

        $workPlaceName = $workPlace->name ?? null; // Handle null if no match
        $workPlaceCensusNo = $workPlace->censusNo ?? null;
        $workPlaceCategoryId = $workPlace->categoryId ?? null;

        $userServiceAppointmentPositionId = DB::table('user_service_appointment_positions')
        ->where('userServiceAppointmentId', $userServiceAppointmentId)
        ->where('current', 1)
        ->where('active', 1)
        ->value('id');

        session(['serviceId' => $userServiceId]);
        session(['appointmentId' => $userServiceAppointmentId]);
        session(['workPlaceId' => $workPlaceId]);
        session(['workPlaceName' => $workPlaceName]);
        session(['workPlaceCensusNo' => $workPlaceCensusNo]);
        session(['workPlaceCategoryId' => $workPlaceCategoryId]);
        //session(['attachmentIds' => $userServiceAppointmentIds]);
        session(['positionId' => $userServiceAppointmentPositionId]);

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

        // $userServiceAppointmentIds = DB::table('user_service_appointments')
        // ->where('userServiceId', $userServiceId)
        // ->where('appointmentType', 2)
        // ->where('current', 1)
        // ->where('active', 1)
        // ->pluck('id');

        

        

        
        //dd(Auth::user());
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
