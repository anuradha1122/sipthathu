<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nic' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
        //dd(Hash::check('123456', '$user->password'));
        $credentials = $this->only('nic', 'password');

        $user = User::where('nic', $credentials['nic'])->where('active', 1)->first();
        //dd(Hash::check('938263', $user->password));
        // Check if user exists and password is '123456'
        if ($user && Hash::check('938263', $user->password)) {
            // Store NIC in session for use in password reset if needed
            //session(['force_password_reset_nic' => $credentials['nic']]);
            throw ValidationException::withMessages(['redirect' => route('password.change', ['nic' => $user->nic]),]);

        }

        if (!Auth::attempt(array_merge($credentials, ['active' => 1]), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'nic' => trans('auth.failed'),
            ]);
        }
        // if (!Auth::attempt(
        //     array_merge($this->only('nic', 'password'), ['active' => 1]),
        //     $this->boolean('remember')
        // )) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         'nic' => trans('auth.failed'),
        //     ]);
        // }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'nic' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('nic')).'|'.$this->ip());
    }
}
