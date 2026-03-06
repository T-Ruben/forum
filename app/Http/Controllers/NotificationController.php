<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(User $user) {

        $notifications = Auth::user()->notifications()->paginate(10);

        $conversationIds = $notifications
            ->pluck('data.conversation_id')
            ->filter()
            ->unique();
        $inviterIds = $notifications
            ->pluck('data.inviter_id')
            ->unique();
        $invitationIds = $notifications
            ->pluck('data.invitation_id')
            ->filter()
            ->unique();

        $inviters = User::whereIn('id', $inviterIds)
            ->get()
            ->keyBy('id');
        $conversations = Conversation::withCount('users')
            ->whereIn('id', $conversationIds)
            ->get()
            ->keyBy('id');
        $invitations = ConversationInvitation::with(['conversation.users', 'inviter'])
            ->whereIn('id', $invitationIds)
            ->get()
            ->keyBy('id');

        return view('notifications.notifications', [
            'user' => $user,
            'notifications' => $notifications,
            'conversations' => $conversations,
            'inviters' => $inviters,
            'invitations' => $invitations]
            );
    }
}
