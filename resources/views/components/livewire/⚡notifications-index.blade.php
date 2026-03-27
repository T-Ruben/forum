<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Notification;
use App\Services\NotificationService;
use \App\Notifications\ProfilePostNotification;
use \App\Notifications\ThreadPostNotification;
use \App\Notifications\ConversationMessageNotification;
use \App\Notifications\ConversationInvitationNotification;

new class extends Component
{
    use WithPagination;

    public $type = null;

    public function setType($typeName) {
        $this->type = match($typeName) {
                'profile_posts' => ProfilePostNotification::class,
                'thread_posts'  => ThreadPostNotification::class,
                'conv_messages' => ConversationMessageNotification::class,
                'conv_invites'  => ConversationInvitationNotification::class,
                default         => null,
            };
        $this->resetPage();
    }

    public function render(NotificationService $notificationService)
    {
        $data = $notificationService->getNotifications(auth()->user(), 20, $this->type);


        return view('components.livewire.⚡notifications-index', [
            'notifications' => $data['notifications'],
            'invitations'   => $data['invitations'],
        ]);
    }
};
?>
<div>
        {{ $notifications->links() }}
        <div class="dark:bg-blue-950 flex items-center justify-between pr-2 border-b-2 border-black">
            <h3 class="text-2xl font-bold p-2 relative ">Notifications</h3>
            <form action="{{ route('notifications.read.all') }}" method="POST">
                @csrf
                <button class="cursor-pointer hover:underline">Mark all as read</button>
            </form>
        </div>

        <div class="flex">
            <aside class="w-1/3 h-fit border-x border-white/50 p-1 m-1">
                <ul class="">
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer
                        {{ is_null($type) ? 'bg-gray-800/50' : '' }}"
                        wire:click="setType('all')" role="button">
                        All Notifications</li>
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer
                        {{ $type === ProfilePostNotification::class ? 'bg-gray-800/50' : '' }}"
                        wire:click="setType('profile_posts')" role="button">
                        Profile Posts</li>
                    <li class="p-1 hover:bg-gray-600 cursor-pointer
                        {{ $type === ThreadPostNotification::class ? 'bg-gray-800/50' : '' }}"
                        wire:click="setType('thread_posts')" role="button">
                        Thread Posts</li>

                    <hr class="h-px my-2 border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-100 to-transparent" />

                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer
                        {{ $type === ConversationMessageNotification::class ? 'bg-gray-800/50' : '' }}"
                        wire:click="setType('conv_messages')" role="button">
                        Conversation Posts</li>
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer
                        {{ $type === ConversationInvitationNotification::class ? 'bg-gray-800/50' : '' }}"
                        wire:click="setType('conv_invites')" role="button">
                        Conversation Invites</li>
                </ul>
            </aside>
            <ul class="h-full w-2/3">
                @forelse ($notifications as $notification)
                    @include('notifications.types.' . class_basename($notification->type), [
                        'notification' => $notification,
                        'variant' => 'page'
                    ])

                    @empty
                    <p class="flex justify-center w-full text-lg mt-3">No notifications</p>
                @endforelse
            </ul>
        </div>
        {{ $notifications->links() }}
</div>
