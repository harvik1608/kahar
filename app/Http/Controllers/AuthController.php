<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('admin.login');
    }

    public function checkLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if(Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }
        return back()->withErrors([
            'error' => 'Invalid email or password. Please try again.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent fixation
        $request->session()->regenerateToken();

        // Redirect to admin login page (or wherever you prefer)
        return redirect('/admin/login')->with('success', 'You have been logged out successfully.');
    }
}
