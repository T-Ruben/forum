<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;
use App\Notifications\ConversationInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ConversationInvitationController extends Controller
{
    public function store(Request $request, Conversation $conversation, User $user) {
        Gate::authorize('invite', $conversation);
        $validated = $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);

        $invitedUser = User::findOrFail($validated['user_id']);

        if($invitedUser) {
            $user = $invitedUser;
        }

        if ($conversation->users()->where('user_id', $request->user_id)->exists()) {
            return back()->withErrors(['search' => 'User already in conversation.']);
        }

        $hasPendingInvitation = ConversationInvitation::where('conversation_id', $conversation->id)
            ->where('invited_user_id', $invitedUser->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingInvitation) {
            return back()->withErrors(['search' => 'An invitation has already been sent to this user.']);
        }

        try {
        $invitation = ConversationInvitation::updateOrCreate([
            'conversation_id' => $conversation->id,
            'invited_user_id' => $user->id,
        ],
        [
            'inviter_id' => Auth::id(),
            'status' => 'pending'
        ]);

        $user->notifications()
            ->where('data->invitation_id', $invitation->id)
            ->delete();

        $user->notify(new ConversationInvitationNotification($invitation));

        return back()->with('success', 'Invitation sent.');
        } catch(\Exception $e) {
            Log::error('Invite failed: ', [
                'conversation_id' => $conversation->id,
                'inviter_id' => Auth::user()->id,
                'invited_user_id' => $request->user_id,
                'error' => $e->getMessage(),
            ]);
            return back()
                ->withErrors(['search' => 'Something went wrong.'])
                ->withInput();
        }
    }

    public function accept(ConversationInvitation $invitation, Request $request)
    {
        Gate::authorize('respond', $invitation);

        if($invitation->invited_user_id !== Auth::id()) {
            abort(403);
        }
        if($invitation->status !== 'pending') {
            abort(403);
        }

        $invitation->conversation->users()->syncWithoutDetaching(Auth::id());

        $invitation->update([
            'status' => 'accepted'
        ]);

        if($request->filled('notification_id')) {
            $notification = Auth::user()
                ->notifications()
                ->where('id', $request->notification_id)
                ->first();

            if($notification) {
                $notification->markAsRead();
            }
        }

        return redirect()->route('conversation.show', $invitation->conversation);
    }

    public function reject(ConversationInvitation $invitation, Request $request) {
        Gate::authorize('respond', $invitation);
        if($invitation->invited_user_id !== Auth::id()) {
            abort(403);
        }
        if($invitation->status !== 'pending') {
            abort(403);
        }

        $invitation->update([
            'status' => 'rejected'
        ]);

        if($request->filled('notification_id')) {
            $notification = Auth::user()
                ->notifications()
                ->where('id', $request->notification_id)
                ->first();

            if($notification) {
                $notification->markAsRead();
            }
        }

        return back();
    }
}
