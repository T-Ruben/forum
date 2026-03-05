 <x-layout>
    <x-header />

    <x-main>
        {{ $notifications->links() }}
        <h2 class="text-2xl font-bold border-b-2 border-black  p-2 relative dark:bg-blue-950">Notifications</h2>

        <ul>
           @forelse ($notifications as $notification)
                        @php
                            $conversation = $conversations[$notification->data['conversation_id']] ?? null;
                            $inviter = $inviters[$notification->data['inviter_id']] ?? null;
                        @endphp
                <li class="p-1 border m-1 flex gap-2">
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

                </li>

            @empty
            <p class="flex justify-center w-full text-lg mt-3">No notifications</p>
           @endforelse
        </ul>
        {{ $notifications->links() }}
    </x-main>

    <x-footer />
 </x-layout>
