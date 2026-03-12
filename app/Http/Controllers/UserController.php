<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function show(User $user, Request $request) {

        $replyTo = null;
        $editPost = null;

        if($request->filled('edit_post')) {
            $editPost = Post::where('profile_user_id', $user->id)
                ->findOrFail($request->edit_post);

            Gate::authorize('update', $editPost);
        }
        elseif($request->filled('reply_to')) {
            $replyTo = Post::where('profile_user_id', $user->id)
                ->findOrFail($request->reply_to);
        }

        $following = $user->following()
            ->with(['followers', 'following', 'posts'])
            ->limit(4)
            ->get();

        $followers = $user->followers()
            ->with(['followers', 'following', 'posts'])
            ->limit(4)
            ->get();

        $posts = $user->profilePosts()
            ->whereNull('parent_id')
            ->with(['user', 'parent', 'replies', 'replies.user'])
            ->withCount('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.show', [
            'user' => $user,
            'posts' => $posts,
            'replyTo' => $replyTo,
            'editPost' => $editPost,
            'following' => $following,
            'followers' => $followers
            ]);
    }

    public function index(User $user, Request $request) {
        $sortOrder = $request->query('sort', 'newest');

        $query = User::query()->with(['followers', 'following', 'posts']);

        match($sortOrder) {
            'newest' => $query->orderBy('created_at', 'desc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $users = $query
            ->paginate(24)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'currentSort' => $sortOrder
            ]);
    }

    public function following(User $user) {
        $following = $user->following()
            ->with(['followers', 'following', 'posts'])
            ->paginate(25);

        return view('users.following', ['following' => $following, 'user' => $user]);
    }

    public function followers(User $user) {
        $followers = $user->followers()
            ->with(['followers', 'following', 'posts'])
            ->paginate(25);

        return view('users.followers', ['followers' => $followers, 'user' => $user]);
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

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        if(Auth::id() === $user->id) {
            Auth::logout();
        }

        $user->delete();

        return redirect('/');
    }

}
