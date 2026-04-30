<?php

namespace App\Http\Controllers;

use App\Http\Requests\Avatar\UpdateAvatarRequest;
use App\Models\User;
use App\Services\AvatarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request, User $user, AvatarService $service)
    {
        Gate::authorize('update', $user);

        $request->validated();

        try {
            $service->updateAvatar(Auth::user(), $request->file('avatar'));

            return back()->with('success', 'Profile image uploaded.');
        } catch(\Exception $e) {
            Log::error('Profile image upload failed', ['error' => $e->getMessage()]);
            return back()
                ->withErrors(['error' => 'Something went wrong while uploading the image. Please try again.'])
                ->withInput();
        };

    }

    public function destroy(User $user, AvatarService $service)
    {
        Gate::authorize('delete', $user);
        try {
            $service->destroyAvatar(Auth::user());
            return back();

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
