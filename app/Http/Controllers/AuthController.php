<?php

namespace App\Http\Controllers;

use App\Support\HardcodedUser;
use Illuminate\Http\Request;

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
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = config('auth.admin');
        $login = $credentials['login'];
        $username = (string) ($admin['username'] ?? '');
        $email = (string) ($admin['email'] ?? '');
        $password = (string) ($admin['password'] ?? '');

        $validLogin = hash_equals($username, $login) || hash_equals($email, $login);
        $validPassword = hash_equals($password, $credentials['password']);

        if (!$validLogin || !$validPassword) {
            return back()
                ->withErrors(['login' => 'Username/email atau password salah.'])
                ->onlyInput('login');
        }

        $user = new HardcodedUser(
            username: $username,
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
