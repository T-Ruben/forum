<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function show(User $user) {



        return view('users.show', compact('user'));
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $user = Auth::user();

        try {
            if($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('avatar')->store('avatars', 'public');

            $user->update(['profile_image' => $path]);

            return back()->with('success', 'Profile image uploaded.');
        } catch(\Exception $e) {
            Log::error('Profile image upload failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors(['error' => 'Something went wrong while uploading the image. Please try again.'])
                ->withInput();
        };

    }
}
