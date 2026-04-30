<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\MessageStoreRequest;
use App\Http\Requests\Message\MessageUpdateRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\ConversationMessageNotification;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MessageStoreRequest $request, MessageService $service)
    {
        Gate::authorize('create', Conversation::class);

        $validated = $request->validated();

    try {
        $service->store($validated);

        return back();
    } catch (\Exception $e) {
        Log::error('Message creation failed:',
                    ['user_id' => Auth::user()->id,
                     'error' => $e->getMessage()]);
        return back()
            ->withErrors(['content' => 'Something went wrong.'])
            ->withInput();
    }

    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MessageUpdateRequest $request, MessageService $service, Message $message)
    {
        Gate::authorize('update', $message);

        $validated = $request->validated();

    try {
        $routeVars = $service->update($validated, $message);

        return redirect()->route('conversation.show', $routeVars);
    } catch (\Exception $e) {
        Log::error('Editing failed: ', ['error', $e->getMessage()]);
        return back()
            ->withErrors(['error' => 'Something went wrong while editing. Please try again.'])
            ->withInput();
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message, MessageService $service)
    {
        Gate::authorize('delete', $message);

        try {
            $routeVars =  $service->destroy($message);

            return redirect()->route('conversation.show', $routeVars);
        } catch(\Exception $e) {
            Log::error('Something went wrong', ['error', $e->getMessage()]);
            return back();
        }

    }
}
