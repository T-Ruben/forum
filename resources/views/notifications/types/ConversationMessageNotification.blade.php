@php
    $type = $notification->data['type'] ?? null;
    $readRoute = route('notifications.read', $notification->id);
    $senderId = $notification->data['sender']['id'];
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
            <a href="{{ route('users.show', $senderId) }}"
                @click.stop>
                <img src="{{ $notification->data['sender']['avatar'] }}" class="w-18 h-18 object-cover ring-1"
                    alt="{{ $notification->data['sender']['name'] }}">
            </a>
        </div>
        <div class="min-w-0 @max-sm:select-none @max-sm:cursor-pointer">
            <a href="{{ route('users.show', $senderId) }}"
                class="hover:underline font-bold"
                @click.stop>
                {{ $notification->data['sender']['name'] }}
            </a>

            {{ $type === 'reply' ? 'replied to your post.' : 'sent a post on your private conversation.' }}

            <p class="block truncate">
                Conversation name:
                <span class="font-bold truncate hover:underline">
                    <a href="{{ route('conversation.show', [
                        $notification->data['conversation']['id'],
                    ]) }}" @click.stop>
                        {{ $notification->data['conversation']['title'] }}
                    </a>
                </span>
            </p>

            <p class="@sm:inline hidden">
                Members: {{ $notification->data['conversation']['members_count'] }}
            </p>

            <p class="text-sm">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    </div>

    @if ($variant !== 'dropdown')
    <div class="flex flex-col justify-between items-end w-1/2">
        <div class="flex justify-end">
            @if (!$notification->read_at)
                    <button class="cursor-pointer hover:underline"
                        wire:click="markAsRead('{{ $notification->id }}')"
                        @click.stop>Mark as read</button>
            @endif
        </div>
    </div>
    @endif
</div>
<hr class="h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-50" />
</li>

