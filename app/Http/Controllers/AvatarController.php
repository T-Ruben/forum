<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class AvatarController extends Controller
{
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);

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


                // $image
                //     ->scaleDown(512)
                //     ->encodeByExtension('jpg', 85)
                //     ->save(storage_path("app/public/avatars/{$filename}"));

                $encoded = $image
                    ->scaleDown(512)
                    ->encodeByExtension('jpg', 85);

                Storage::disk('public')->put("avatars/{$filename}", (string) $encoded);

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

    public function destroy()
    {
        $user = Auth::user();
        try {
            if($user->profile_image) {
                Storage::disk('public')->delete('avatars/' . $user->profile_image);
                $user->update(['profile_image' => null]);
                return back();
            }
        } catch(\Exception $e) {
                Log::error('Avatar deletion failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                return back()
                    ->withErrors(['error' => 'Something must have gone wrong while deleting the avatar. Please try again.']);
        };
    }
}
