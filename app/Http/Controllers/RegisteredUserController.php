<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    public function create() {
        return view('auth.register');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', 'unique:users', 'regex:/^\S+$/' ],
            'email' => ['required', 'string', 'email',  'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['required'],
        ]);
        try {
            $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'password'=> Hash::make($validated['password']),
            'profile_image'=> null
        ]);

            Log::info('New user registered', ['user_id' => $user->id]);


        return redirect()
            ->route('login')
            ->with('success', 'Account successfully created! You may now log in.');

        } catch (\Exception $e) {
            Log::error('Account creation failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors('error', 'Something went wrong while creating your account. Please try again.')
                ->withInput();
        }
    }

}
