<?php

namespace App\Http\Requests\Auth;

use App\Models\FailedLoginAttempt;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $tenantId = tenant()?->getTenantKey();

        $user = User::query()
            ->when($tenantId, fn ($query) => $query->where('tenant_id', $tenantId))
            ->when(! $tenantId, fn ($query) => $query->whereNull('tenant_id'))
            ->where('email', $this->string('email'))
            ->first();

        if (! $user || ! Hash::check($this->string('password'), $user->password)) {
            FailedLoginAttempt::query()->create([
                'tenant_id' => $tenantId,
                'email' => $this->string('email'),
                'ip_address' => $this->ip(),
                'user_agent' => (string) $this->userAgent(),
            ]);

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        $user->forceFill([
            'last_login_at' => now(),
            'failed_login_attempts' => 0,
        ])->save();

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
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
        $tenantKey = tenant()?->getTenantKey() ?? 'central';

        return Str::transliterate(Str::lower($this->string('email')).'|'.$tenantKey.'|'.$this->ip());
    }
}
