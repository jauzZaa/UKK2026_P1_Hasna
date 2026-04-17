<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // ← LOG
        ActivityLog::log('login', 'auth', 'User login ke sistem');

        $role = strtolower(auth()->user()->role);

        if ($role === 'user') {
            return redirect()->route('alat.tampil');
        } elseif ($role === 'employee') {
            return redirect()->route('peminjaman.tampil');
        } else {
            // Admin masuk sini
            return redirect()->route('dashboard');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        // ← LOG (sebelum logout)
        ActivityLog::log('logout', 'auth', 'User logout dari sistem');

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
