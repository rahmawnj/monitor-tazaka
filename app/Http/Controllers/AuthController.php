<?php

namespace App\Http\Controllers;

use App\Support\HardcodedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = (string) env('ADMIN_EMAIL');
        $password = (string) env('ADMIN_PASSWORD');

        if (! hash_equals($email, $credentials['email']) || ! hash_equals($password, $credentials['password'])) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $user = new HardcodedUser(
            username: (string) env('ADMIN_USERNAME', 'Admin'),
            email: $email,
        );

        $request->session()->regenerate();
        $request->session()->put('user', $user);
        $request->session()->put('authenticated', true);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
