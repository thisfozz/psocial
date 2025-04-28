<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse{
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],

            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:15', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $email = strtolower(trim($request->email));
        $hash = md5($email);
        $fullName = $request->first_name . ' ' . $request->last_name;

        $gravatarCheckUrl = "https://www.gravatar.com/avatar/$hash?d=404";
        $gravatarBase = "https://www.gravatar.com/avatar/$hash";

        $uiavatars = 'https://ui-avatars.com/api/?name=' . urlencode($fullName) . '&background=000000&color=fff&rounded=true';

        $headers = @get_headers($gravatarCheckUrl);
        if ($headers && strpos($headers[0], '200') !== false) {
            $avatar = $gravatarBase;
        } else {
            $avatar = $uiavatars;
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'avatar_path' => $avatar,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended(route('social.show', ['id' => auth()->id()], false));
    }
}