<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create() {
        return view('auth.register');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:users' ],
            'email' => ['required', 'string', 'email',  'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password'=> Hash::make($validated['password']),
            'profile_image'=> null
        ]);

        Auth::login($user);


        return redirect('/');
    }

}
