<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
