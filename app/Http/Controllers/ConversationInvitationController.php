<?php

namespace App\Http\Controllers;

use App\Http\Requests\Conversation\ConversationInvitationRequest;
use App\Models\Conversation;
use App\Models\ConversationInvitation;
use App\Models\User;
use App\Notifications\ConversationInvitationNotification;
use App\Services\ConversationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ConversationInvitationController extends Controller
{
    public function store(ConversationInvitationRequest $request, ConversationService $service, Conversation $conversation, User $user) {
        Gate::authorize('invite', $conversation);
        $validated = $request->validated();

        try {
        $service->storeInvitation($validated, $conversation);

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

    public function accept(ConversationInvitation $invitation, Request $request, ConversationService $service)
    {
        Gate::authorize('respond', $invitation);

        $service->accept($invitation, $request);

        return redirect()->route('conversation.show', $invitation->conversation);
    }

    public function reject(ConversationInvitation $invitation, Request $request, ConversationService $service) {
        Gate::authorize('respond', $invitation);

        $service->reject($invitation, $request);

        return back();
    }
}
