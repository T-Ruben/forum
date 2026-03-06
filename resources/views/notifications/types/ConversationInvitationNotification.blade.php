@php
    $conversation = $conversations[$notification->data['conversation_id']] ?? null;
    $inviter = $inviters[$notification->data['inviter_id']] ?? null;
    $invitation = $invitations[$notification->data['invitation_id']] ?? null;
@endphp
<li class="p-1 border m-1 flex justify-between">
    <div class="flex gap-2">
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

    <div class="w-fit flex items-end gap-2">
        <form action="{{ route('conversation.accept', $invitation) }}" method="POST" class="">
            @csrf
            <button class="w-16 cursor-pointer border p-0.5 rounded-sm dark:bg-blue-900 hover:dark:bg-blue-700 duration-300">
                Accept
            </button>
        </form>
        <form action="{{ route('conversation.reject', $invitation) }}" method="POST" class="">
            @csrf
            <button class="w-16 cursor-pointer border p-0.5 rounded-sm dark:bg-blue-900 hover:dark:bg-blue-700 duration-300">
                Reject
            </button>
        </form>
    </div>
</li>
