<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            dd($e->getMessage()); // เพิ่มบรรทัดนี้
            return redirect()->route('login')
                ->withErrors(['google' => 'ไม่สามารถเข้าสู่ระบบด้วย Google ได้ กรุณาลองใหม่อีกครั้ง']);
        }

        if (! $googleUser->getEmail()) {
            return redirect()->route('login')
                ->withErrors(['google' => 'บัญชี Google นี้ไม่มีอีเมลให้ใช้งาน']);
        }

        $user = User::firstOrNew(['email' => $googleUser->getEmail()]);

        $user->fill([
            'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'email_verified_at' => now(),
        ]);

        if (! $user->exists) {
            $user->password = Hash::make(Str::random(32));
        }

        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('todos.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}
