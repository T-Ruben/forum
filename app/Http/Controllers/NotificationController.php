<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(NotificationService $notificationService)
    {
        $data = $notificationService->getNotifications(Auth::user(), 20);

        return view('notifications.notifications', $data);
    }

    public function dropdown(NotificationService $service)
    {
        $data = $service->getNotifications(Auth::user(), 15);

        return view('notifications.partials.dropdown', $data);
    }

    public function markAsRead($notificationId) {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($notificationId);

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead() {
        Auth::user()->unreadNotifications
            ->markAsRead();

        return back();
    }
}
