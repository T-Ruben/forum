<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\Post;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
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

        return view('notifications.dropdown', $data);
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back();
    }

    public function markAllAsRead() {
        Auth::user()->unreadNotifications
            ->markAsRead();

        return back();
    }

    public function jump(Post $post) {
        $page = $post->getPageNumberProfile();

         Auth::user()->unreadNotifications()
            ->where('data->post_id', $post->id)
            ->get()
            ->markAsRead();

        return redirect()->to(
            route('users.show', ['user' => $post->profile_user_id, 'page' => $page]) . "#post-" . $post->id
        );
    }
}
