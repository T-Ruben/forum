@php
    $conversation = $conversations[$notification->data['conversation_id']] ?? null;
    $inviter = $inviters[$notification->data['inviter_id']] ?? null;
    $invitation = $invitations[$notification->data['invitation_id']] ?? null;
    $readRoute = route('notifications.read', $notification->id);
@endphp
<li class="@container">
<div class="p-1 h-fit border border-0.5 m-1 flex justify-between
    {{ $notification->read_at == false ? 'bg-gray-300/20' : '' }}
     @max-sm:border-0">
    <div
        @if (($variant ?? 'page') === 'dropdown')
            x-data
            @click="
                fetch('{{ $readRoute }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    window.location.href = '{{ route('notifications.index', Auth::user()) }}'
                })
            "
        @endif
        class="flex gap-2 h-full w-1/2 @max-sm:w-full @max-sm:cursor-pointer"
    >
        <div class="shrink-0 hidden @sm:block">
            <a href="{{ route('users.show', $inviter) }}"
                @click.stop>
                <img src="{{ $inviter->profile_image_url }}" class="w-18 h-18 object-cover" alt="{{ $inviter->display_name }}">
            </a>
        </div>
        <div class="min-w-0 @max-sm:select-none @max-sm:cursor-pointer">
            <a href="{{ route('users.show', $inviter) }}"
                class="hover:underline font-bold"
                @click.stop>
                {{ $inviter->display_name }}
            </a>invited you to a conversation.
            <p class="block truncate">Conversation name: <span class="font-bold truncate">{{ $conversation->title }}</span></p>
            <p class="@sm:inline hidden">Members: {{ $conversation->users_count }}</p>
            <p class="text-sm">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
    </div>

    @if ($variant !== 'dropdown')
    <div class="flex flex-col justify-between items-end w-1/2">
        <div class="flex justify-end">
            @if (!$notification->read_at)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" @click.stop>
                    @csrf
                    <button class="cursor-pointer hover:underline">Mark as read</button>
                </form>
            @endif
        </div>
        <div class="gap-2 flex">
            @if($invitation->status === 'pending')
                <form action="{{ route('conversation.accept', $invitation) }}" method="POST" class="" @click.stop>
                    @csrf
                    <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                    <button class="w-16 cursor-pointer border p-0.5 rounded-sm dark:bg-blue-900 hover:dark:bg-blue-700 duration-300">
                        Accept
                    </button>
                </form>
                <form action="{{ route('conversation.reject', $invitation) }}" method="POST" class="" @click.stop>
                    @csrf
                    <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                    <button class="w-16 cursor-pointer border p-0.5 rounded-sm dark:bg-blue-900 hover:dark:bg-blue-700 duration-300">
                        Reject
                    </button>
                </form>
            @elseif($invitation->status === 'accepted' && $notification->read_at)
                <span class="text-green-500 font-bold">Accepted</span>
            @elseif($invitation->status === 'rejected' && $notification->read_at)
                <span class="text-red-500 font-bold">Rejected</span>
            @endif
        </div>
    </div>
    @endif
</div>
<hr class="h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-50" />
</li>

