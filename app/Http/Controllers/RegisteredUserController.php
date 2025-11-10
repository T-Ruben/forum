<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function create() {
        return view('auth.register');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:20', 'unique:users', 'regex:/^[a-zA-Z0-9._-]{3, 20}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'max:128', 'confirmed', Password::defaults()],
            'gender' => ['required'],
            'location' => ['nullable', 'string', 'max:75'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'day' => ['required', 'integer', 'min:1', 'max:31']
        ]);

        $date_of_birth = sprintf('%04d-%02d-%02d',
            $request->year,
            $request->month,
            $request->day
        );

        try {
            $dob = Carbon::createFromFormat('Y-m-d', $date_of_birth);
            if ($dob->isFuture()) {
                throw ValidationException::withMessages([
                    'date_of_birth' => 'Your birth date cannot be in the future.',
                ]);
            }
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'date_of_birth' => 'Invalid date provided.',
            ]);
        }

        $validated['date_of_birth'] = $dob->format('Y-m-d');

        try {
            $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'location' => $validated['location'] ?? null,
            'date_of_birth' => $validated['date_of_birth'],
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
                ->withErrors(['error' => 'Something went wrong while creating your account. Please try again.'])
                ->withInput();
        }
    }

}
