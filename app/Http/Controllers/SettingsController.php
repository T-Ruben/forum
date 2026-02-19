<?php

namespace App\Http\Controllers;

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

    public function threads(Thread $threads) {
        $userThreads = Auth::user()->threads()
            ->with('posts')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.threads', ['user' => Auth::user(), 'threads' => $userThreads]);
    }

}
