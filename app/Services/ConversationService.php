<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;
use App\Notifications\ConversationInvitationNotification;
use Auth;
use Illuminate\Support\Facades\DB;

class ConversationService
{
    /**
     * Create a new class instance.
     */
    public function store(array $validated, User $inviter, User $invitee)
    {
        return DB::transaction(function () use ($validated, $inviter, $invitee) {

        $conversation = Conversation::create([
            'title' => $validated['title'],
        ]);

        $conversation->users()->attach([$inviter->id]);

        $invitation = ConversationInvitation::create([
            'conversation_id' => $conversation->id,
            'inviter_id' => $inviter->id,
            'invited_user_id' => $invitee->id,
        ]);

        $conversation->messages()->create([
            'conversation_id' => $conversation->id,
            'user_id' => $inviter->id,
            'content' => $validated['content']
        ]);

        $invitee->notify(new ConversationInvitationNotification($invitation));

        return $conversation;
        });
    }

    public function storeInvitation(array $validated, object $conversation) {
        $invitedUser = User::findOrFail($validated['user_id']);

        if(!$invitedUser) {
            return back()->withErrors(['search' => "The user you are trying to invite doesn't exist or a problem has occured."]);
        }

        if ($conversation->users()->where('user_id', $invitedUser->id)->exists()) {
            return back()->withErrors(['search' => 'User already in conversation.']);
        }

        $hasPendingInvitation = ConversationInvitation::where('conversation_id', $conversation->id)
            ->where('invited_user_id', $invitedUser->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPendingInvitation) {
            return back()->withErrors(['search' => 'An invitation has already been sent to this user.']);
        }

        $invitation = ConversationInvitation::updateOrCreate([
            'conversation_id' => $conversation->id,
            'invited_user_id' => $invitedUser->id,
        ],
        [
            'inviter_id' => Auth::id(),
            'status' => 'pending'
        ]);

        $invitedUser->notifications()
            ->where('data->invitation_id', $invitation->id)
            ->delete();

        $invitedUser->notify(new ConversationInvitationNotification($invitation));
    }

    public function accept(object $invitation, object $request) {
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
    }

    public function reject(object $invitation, object $request) {
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
    }
}
