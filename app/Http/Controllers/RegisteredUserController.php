<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\Register;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
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

    public function store(RegisterRequest $request, AuthService $service) {
        $validated = $request->validated();

        try {
            $user = $service->register($validated);

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
