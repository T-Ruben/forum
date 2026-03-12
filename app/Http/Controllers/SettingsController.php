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

        $query = Auth::user()->threads()
            ->with('posts');

        if($sortOrder === 'latest_activity') {
            $query->orderBy('updated_at', 'desc');
        } elseif($sortOrder === 'asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $userThreads = $query
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

        $query = Auth::user()->conversations()->with(['messages', 'users']);

        if($sortOrder === 'latest_activity') {
            $query->orderBy('updated_at', 'desc');
        } elseif($sortOrder === 'asc') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $conversations = $query
            ->paginate(10)
            ->withQueryString();

        return view('users.conversations', [
            'user' => Auth::user(),
            'conversations' => $conversations,
            'currentSort' => $sortOrder
            ]);
    }
}
