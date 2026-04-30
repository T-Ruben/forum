<?php

namespace App\Services;

use App\Models\User;
use Image;
use Log;
use Storage;

class AvatarService
{
    /**
     * Create a new class instance.
     */
    public function updateAvatar(User $user, $file)
    {
        if($user->profile_image) {
            Storage::disk('public')->delete('avatars/' . $user->profile_image);
        }

        $image = Image::read($file);

        $filename = 'avatar_' . $user->id . '.jpg';

        Log::info('Avatar upload:', [
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
        ]);

        $encoded = $image
            ->scaleDown(512)
            ->encodeByExtension('jpg', 85);


        Storage::disk('public')->put("avatars/{$filename}", (string) $encoded);

        $user->update([
            'profile_image' => $filename,
        ]);
    }

    public function destroyAvatar(User $user) {
        if($user->profile_image) {
            Storage::disk('public')->delete('avatars/' . $user->profile_image);
            $user->update(['profile_image' => null]);
        }
    }
}
