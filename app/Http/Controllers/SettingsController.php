<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{

    public function personal() {
        return view('users.personal', ['user' => Auth::user()]);
    }
    public function privacy() {
        return view('users.privacy', ['user' => Auth::user()]);
    }

    public function threads(Request $request) {
        $sortOrder = $request->query('sort', 'latest_activity');


        $query = Auth::user()->threads()->withCount(['posts']);

        [$column, $direction] = match ($sortOrder) {
            'latest_activity' => ['updated_at', 'desc'],
            'asc', 'oldest'   => ['created_at', 'asc'],
            'most_posts'   => ['posts_count', 'desc'],
            default           => ['created_at', 'desc'],
        };

        $userThreads = $query->orderBy($column, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('users.threads', [
            'user' => Auth::user(),
            'threads' => $userThreads,
            'currentSort' => $sortOrder
            ]);
    }

    public function conversations(Request $request) {
        $sortOrder = $request->query('sort', 'latest_activity');

        $query = Auth::user()->conversations()->withCount(['messages', 'users']);

        [$column, $direction] = match ($sortOrder) {
            'latest_activity' => ['updated_at', 'desc'],
            'asc', 'oldest'   => ['created_at', 'asc'],
            'most_messages'   => ['messages_count', 'desc'],
            'most_members'    => ['users_count', 'desc'],
            default           => ['created_at', 'desc'],
        };

        $conversations = $query->orderBy($column, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('users.conversations', [
            'user' => Auth::user(),
            'conversations' => $conversations,
            'currentSort' => $sortOrder
            ]);
    }
}
