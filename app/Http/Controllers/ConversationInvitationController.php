<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;
use App\Notifications\ConversationInvitationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConversationInvitationController extends Controller
{
    public function store(Request $request, Conversation $conversation, User $user) {
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
        $invitation = ConversationInvitation::create([
            'conversation_id' => $conversation->id,
            'inviter_id' => Auth::id(),
            'invited_user_id' => $user->id,
            'status' => 'pending'
        ]);

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
                ->withErrors(['content' => 'Something went wrong.'])
                ->withInput();
        }
    }

    public function accept(ConversationInvitation $invitation)
    {
        if($invitation->invited_user_id !== Auth::id()) {
            abort(403);
        }

        $invitation->conversation->users()->syncWithoutDetaching(Auth::id());

        $invitation->update([
            'status' => 'accepted'
        ]);

        return redirect()->route('conversation.show', $invitation->conversation);
    }

    public function reject(ConversationInvitation $invitation) {
        if($invitation->invited_user_id !== Auth::id()) {
            abort(403);
        }

        $invitation->update([
            'status' => 'rejected'
        ]);

        return back();
    }
}
