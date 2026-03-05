<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(User $user, Conversation $conversation) {

        $notifications = Auth::user()->notifications()->paginate(10);

        $conversationIds = $notifications
            ->pluck('data.conversation_id')
            ->filter()
            ->unique();
        $inviterIds = $notifications
            ->pluck('data.inviter_id')
            ->unique();

        $inviters = User::whereIn('id', $inviterIds)
            ->get()
            ->keyBy('id');

        $conversations = Conversation::withCount('users')
            ->whereIn('id', $conversationIds)
            ->get()
            ->keyBy('id');

        return view('users.notifications', [
            'user' => $user,
            'notifications' => $notifications,
            'conversations' => $conversations,
            'inviters' => $inviters]
            );
    }
}
