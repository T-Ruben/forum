<?php

namespace App\Http\Controllers;

use App\Models\Post;
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
    public function show(User $user, Request $request, Post $post) {
        $replyTo = null;

        if($request->filled('reply_to')) {
            $replyTo = Post::where('profile_user_id', $user->id)
                ->findOrFail($request->reply_to);
        }

$posts = $user->profilePosts()
    ->whereNull('parent_id')
    ->with(['user', 'recursiveReplies', 'parent']) // Just load the top level + the recursion
    ->orderBy('created_at', 'desc')
    ->paginate(10);

        return view('users.show', [
            'user' => $user,
            'posts' => $posts,
            'replyTo' => $replyTo,
            ]);
    }

    public function follow(User $user) {
        $auth = Auth::user();

        if ($auth->id === $user->id) {
            return abort(422, 'Cannot follow yourself.');
        }

        $auth->following()->syncWithoutDetaching([$user->id]);
        return back();
    }

    public function unfollow(User $user) {
        $auth = Auth::user();
        $auth->following()->detach([$user->id]);
        return back();
    }

    public function destroy()
    {
        Auth::user()->delete(Auth::user()->id);
        return redirect('/');
    }

}
