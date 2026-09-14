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
$login = trim($credentials['login']);
$username = trim((string) ($admin['username'] ?? ''));
$email = trim((string) ($admin['email'] ?? ''));
$password = (string) ($admin['password'] ?? '');

$validLogin = ($username !== '' && $login === $username)
    || ($email !== '' && $login === $email);

$validPassword = $password !== ''
    && $credentials['password'] === $password;
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
