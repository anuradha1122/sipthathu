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
use Illuminate\Validation\ValidationException;

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
        //$request->authenticate();

        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            $errors = $e->errors();

            // 🔁 This is your custom redirect logic
            if (isset($errors['redirect'])) {
                return redirect($errors['redirect'][0]);
            }

            // rethrow if it's a regular validation error
            throw $e;
        }

        // Proceed normally
        $request->session()->regenerate();

        $userService = DB::table('user_in_services')
        ->where('userId', Auth::id())
        ->where('current', 1)
        ->where('active', 1)
        ->select('id', 'serviceId')
        ->first();

        $userServiceId = $userService->id;
        $serviceId = $userService->serviceId;


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

        $positionId = DB::table('user_service_appointment_positions')
        ->where('userServiceAppointmentId', $userServiceAppointmentId)
        ->where('current', 1)
        ->where('active', 1)
        ->value('positionId');

        session(['userServiceId' => $userServiceId]);
        session(['serviceId' => $serviceId]);
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
        //dd(session('higherZoneId'));
        // $userServiceAppointmentIds = DB::table('user_service_appointments')
        // ->where('userServiceId', $userServiceId)
        // ->where('appointmentType', 2)
        // ->where('current', 1)
        // ->where('active', 1)
        // ->pluck('id');


        // Get all session data


        //dd(session()->all());

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
