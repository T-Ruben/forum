<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function register(array $validated)
    {
        try {
            $date_of_birth = sprintf('%04d-%02d-%02d',
            $validated['year'],
            $validated['month'],
            $validated['day']
            );

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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'location' => $validated['location'] ?? null,
            'date_of_birth' => $validated['date_of_birth'],
            'password'=> Hash::make($validated['password']),
            'profile_image'=> null
        ]);

        return $user;

    }
}
