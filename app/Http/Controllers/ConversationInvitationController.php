<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\ConversationInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConversationInvitationController extends Controller
{
    public function store(Request $request, Conversation $conversation) {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($conversation->users()->where('user_id', $request->user_id)->exists()) {
            return back()->withErrors(['search' => 'User already in conversation.']);
        }

        try {
        ConversationInvitation::create([
            'conversation_id' => $conversation->id,
            'inviter_id' => Auth::user()->id,
            'invited_user_id' => $request->user_id,
        ]);

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

    public function accept() {

    }

    public function reject() {

    }
}
