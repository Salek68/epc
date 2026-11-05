<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // صفحه ورود
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('seller.register');
        }

        return view('auth.login');
    }

    // بررسی اطلاعات ورود
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('seller.register'));
        }

        return back()
            ->withErrors(['email' => 'ایمیل یا رمز عبور اشتباه است.'])
            ->onlyInput('email');
    }

    // صفحه ثبت نام
    public function register()
    {
        if (Auth::check()) {
            return redirect()->route('seller.register');
        }

        return view('auth.register');
    }

    // ذخیره کاربر جدید
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->is_admin = false;

        $user->save();

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->route('seller.register');
    }

    // خروج از حساب
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
