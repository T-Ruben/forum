@php
    $conversation = $conversations[$notification->data['conversation_id']] ?? null;
    $inviter = $inviters[$notification->data['inviter_id']] ?? null;
    $invitation = $invitations[$notification->data['invitation_id']] ?? null;
@endphp
<li class="p-1 h-21 border m-1 flex justify-between {{ $notification->read_at == false ? 'bg-gray-300/20' : '' }}">
    <div class="flex gap-2 h-full">
        <div>
            <a href="{{ route('users.show', $inviter) }}">
                <img src="{{ $inviter->profile_image_url }}" class="w-18 h-18 object-cover" alt="{{ $inviter->display_name }}">
            </a>
        </div>
        <div>
            <a href="{{ route('users.show', $inviter) }}"
                class="hover:underline font-bold">
                {{ $inviter->display_name }}
            </a>invited you to a conversation.
            <p>Conversation name: <span class="font-bold">{{ $conversation->title }}</span></p>
            <p>Members: {{ $conversation->users_count }}</p>
        </div>
    </div>

    <div class="flex flex-col justify-between">
        <div class="flex justify-end">
            @if (!$notification->read_at)
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                    @csrf
                    <button class="cursor-pointer hover:underline">Mark as read</button>
                </form>
            @endif
        </div>
        <div class="flex gap-2">
            @if($invitation->status === 'pending')
                <form action="{{ route('conversation.accept', $invitation) }}" method="POST" class="">
                    @csrf
                    <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                    <button class="w-16 cursor-pointer border p-0.5 rounded-sm dark:bg-blue-900 hover:dark:bg-blue-700 duration-300">
                        Accept
                    </button>
                </form>
                <form action="{{ route('conversation.reject', $invitation) }}" method="POST" class="">
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
</li>
