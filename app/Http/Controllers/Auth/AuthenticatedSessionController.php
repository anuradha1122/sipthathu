<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['redirect'])) {
                return redirect($errors['redirect'][0]);
            }
            throw $e;
        }

        $request->session()->regenerate();

        

        Auth::user()->load([
            'currentUserInService.currentAppointment.workPlace.school.office.higherOffice.higherOffice',
            'currentUserInService.currentAppointment.workPlace.office.higherOffice.higherOffice',
            'currentUserInService.currentAppointment.workPlace.ministry.office',
            'currentUserInService.currentAppointment.currentPosition'
        ]);
        // Get work place name
        //$workPlaceName = Auth::user()->currentUserInService?->currentAppointment?->workPlace?->catagoryId;

        // dd($workPlaceName);
        // dd(Auth::user()->toArray());
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
