<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('admin_akademik.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau kata sandi tidak sesuai.');
        }

        $request->session()->regenerate();

        return $this->redirectByRole($request->user()->role);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Anda telah berhasil keluar dari sistem.');
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin_akademik' => redirect()->route('admin.dashboard'),
            'guru_mapel' => redirect()->route('guru.dashboard'),
            'siswa_wali' => redirect()->route('siswa.dashboard'),
            default => $this->forceLogoutOnInvalidRole(),
        };
    }

    private function forceLogoutOnInvalidRole(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')
            ->with('error', 'Peran akun tidak valid.');
    }
}
