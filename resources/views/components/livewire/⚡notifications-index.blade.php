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

    public function formatCount($count) {
        return $count >= 100 ? '99+' : $count;
    }

    public function getFiltersProperty(){
        $user = Auth::user();
        return [
            ['key' => 'all',
             'type' => 'total',
             'label' => 'All Notifications',
             'value' => null,
             'count' => $user->total ?? 0,
             'has_hr' => false],

            ['key' => 'profile_posts',
             'type' => 'profile',
             'label' => 'Profile Posts',
             'value' => ProfilePostNotification::class,
             'count' => $user->profile ?? 0,
             'has_hr' => false],

            ['key' => 'thread_posts',
             'type' => 'thread',
             'label' => 'Thread Posts',
             'value' => ThreadPostNotification::class,
             'count' => $user->thread ?? 0,
             'has_hr' => true],

            ['key' => 'conv_messages',
             'type' => 'convMessage',
             'label' => 'Conversation Posts',
             'value' => ConversationMessageNotification::class,
             'count' => $user->convMessage ?? 0,
             'has_hr' => false],

            ['key' => 'conv_invites',
             'type' => 'convInvite',
             'label' => 'Conversation Invites',
             'value' => ConversationInvitationNotification::class,
             'count' => $user->convInvite ?? 0,
             'has_hr' => false]
        ];
    }

    public function markAsRead($id) {
        $notification = Auth::user()->notifications()->findOrFail($id);

        $notification->markAsRead();
    }

    public function render(NotificationService $notificationService)
    {
        $data = $notificationService->getNotifications(auth()->user(), 20, $this->type);

        return view('components.livewire.⚡notifications-index', [
            'notifications' => $data['notifications'],
            'invitations'   => $data['invitations'],
            'user' => $data['user']
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
                    @foreach ($this->filters as $filter)
                        <li class="p-1 {{ $filter['has_hr'] === false ? 'border-b border-gray-400/50' : '' }}
                                hover:bg-gray-600 cursor-pointer flex justify-between
                            {{ $type === $filter['value'] ? 'bg-gray-800/50' : '' }}"
                            wire:click="setType('{{ $filter['key'] }}')" role="button">
                            {{ $filter['label'] }}
                            <span class ="flex items-center justify-center w-8 h-6 rounded-lg text-xs
                                font-bold text-white border border-black text-shadow-lg/25 select-none
                                {{ $this->formatCount($filter['count']) >= 1 ? 'bg-red-600' : 'bg-gray-700' }}">
                                {{ $this->formatCount($filter['count']) }}
                            </span>
                        </li>
                        @if ($filter['has_hr'] ?? false)
                            <hr class="h-px my-2 border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-100 to-transparent" />
                        @endif
                    @endforeach
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
