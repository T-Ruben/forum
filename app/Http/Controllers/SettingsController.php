<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Thread;
use App\Models\User;
use App\Services\SettingsService;
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

    public function threads(Request $request, SettingsService $service) {
        $sortOrder = $request->query('sort', 'latest_activity');


        $routeVar = $service->threadsService($sortOrder);

        return view('users.threads', $routeVar);
    }

    public function conversations(Request $request, SettingsService $service) {
        $sortOrder = $request->query('sort', 'latest_activity');

        $routeVar = $service->conversationsService($sortOrder);


        return view('users.conversations', $routeVar);
    }

    public function notifications() {

        return view('users.notifications');
    }
}
