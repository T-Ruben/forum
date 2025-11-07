<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

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
                Storage::disk('public')->delete('avatars/' . $user->profile_image);
            }

                $image = Image::read($request->file('avatar'));

                $filename = 'avatar_' . Auth::user()->id . '_' . time() . '.jpg';

                Log::info('Avatar upload:', [
                    'mime' => $request->file('avatar')->getMimeType(),
                    'size' => $request->file('avatar')->getSize(),
                    'original_name' => $request->file('avatar')->getClientOriginalName(),
                ]);

                $image
                    ->scaleDown(512)
                    ->encodeByExtension('jpg', 85)
                    ->save(storage_path("app/public/avatars/{$filename}"));

                $user->update([
                    'profile_image' => $filename,
                ]);

            return back()->with('success', 'Profile image uploaded.');
        } catch(\Exception $e) {
            Log::error('Profile image upload failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors(['error' => 'Something went wrong while uploading the image. Please try again.'])
                ->withInput();
        };

    }
}
