<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Services\UserService;
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
    public function show(User $user, UserService $service) {

    // Quite a bit of refactoring took place here using AI for editing/replying with livewire.

        $data = $service->prepareUserShow($user);

        return view('users.show', $data);
    }

    public function index(User $user, Request $request, UserService $service) {
        $sortOrder = $request->query('sort', 'newest');

        $data = $service->prepareUserIndex($sortOrder);

        return view('users.index', $data);
    }

    public function following(User $user, UserService $service) {

        $data = $service->prepareUserFollowing($user);

        return view('users.following', $data);
    }

    public function followers(User $user, UserService $service) {

        $data = $service->prepareUserFollowers($user);

        return view('users.followers', $data);
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

        $user->delete($user);

        return redirect('/');
    }

}
