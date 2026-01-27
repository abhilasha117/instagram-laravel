<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        if (Auth::check()) {
            // Already logged in → redirect to profile
            return redirect()->route('profile.get', Auth::user()->username);
        }

        return view('login');
    }

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required'
        ]);
        $username = $request->username;
        session(['username' => $username]);
        // dd($request);
    return redirect()->route('profile.get', ['username' => $username]);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
