<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        if (auth()->user()->status === 'suspended') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Akun Anda sedang dinonaktifkan. Hubungi admin.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,umkm',
            'business_name' => 'required_if:role,umkm|nullable|string|max:255',
        ]);

        $email = $request->email;
        $domain = strtolower(substr($email, strrpos($email, '@') + 1));
        $isAcId = $domain === 'ac.id' || str_ends_with($domain, '.ac.id');
        $isStudent = $request->role === 'student';

        // Mahasiswa: .ac.id auto-verified; gmail menunggu KTM. UMKM aktif langsung.
        $status = !$isStudent || $isAcId ? 'active' : 'pending_ktm';
        $verifiedAt = $isStudent && $isAcId ? now() : null;

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $status,
            'student_verified_at' => $verifiedAt,
            'ktm_path' => null,
            'business_name' => $request->role === 'umkm' ? $request->business_name : null,
        ]);

        if ($request->role === 'student') {
            Wallet::create([
                'student_id' => $user->id,
                'balance' => 0,
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
