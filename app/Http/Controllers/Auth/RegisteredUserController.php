<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * عرض صفحة التسجيل
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * معالجة تسجيل المستخدم
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle avatar upload
        $imageName = null;
        if ($request->hasFile('avatar')) {
            $imageName = $request->file('avatar')->store('profile_images', 'public');
        }

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => $imageName,
        ]);

        // Log the user in immediately
        Auth::login($user);

        // Redirect to dashboard or home page
        return redirect()->route('Chatly')->with('success', 'Welcome! You are now logged in.');
    }
}
