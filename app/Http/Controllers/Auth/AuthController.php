<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @throws ValidationException
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('Email atau kata sandi yang Anda masukkan salah.'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user && $user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('profile.show'));
    }

    /**
     * Display the registration view.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'password' => Hash::make($request->validated('password')),
                'role' => 'alumni',
                'status' => 'pending',
            ]);

            $year = $request->validated('graduation_year');

            Alumnus::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'slug' => Str::slug($user->name.'-'.Str::random(5)),
                'level' => $request->validated('level', 'sma'),
                'class_year' => $year ? substr((string) $year, -2) : null,
                'full_year' => $year ? (string) $year : null,
                'profession' => $request->validated('profession'),
                'institution' => $request->validated('institution'),
                'domicile' => $request->validated('domicile'),
                'email' => $user->email,
                'phone' => $user->phone,
                'is_verified' => false,
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect()->route('profile.show')->with('success', 'Selamat datang! Akun alumni Anda berhasil didaftarkan dan sedang menunggu verifikasi pengurus.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
