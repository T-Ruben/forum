<x-settings.aside>
    <x-slot:title>Conversations</x-slot:title>
        <div class="mt-4">
            {{ $conversations->links() }}
        </div>
    <div class=" border-l pl-2">

        <ul class="">
            @forelse ($conversations as $conversation)
                <li class="text-lg mb-2 ">
                    <div>
                        <div class="min-w-0 flex gap-1">
                            <span class=" shrink-0">Conversation name: </span>
                            <a href="{{ route('conversation.show', ['conversation' => $conversation]) }}"
                                class="hover:underline duration-200 font-bold block min-w-0 truncate">
                                    <span class="truncate">{{ $conversation->title }}</span>
                            </a>
                        </div>
                        <div>
                            <span class="text-sm">Messages: {{ $conversation->messages->count() }}</span>
                        </div>
                    </div>
                    <form action="{{ route('conversation.leave', $conversation) }}" method="POST" class="mb-2 flex justify-end">
                        @csrf
                        @method('DELETE')

                        <x-forms.form-button onclick="return confirm('Are you sure?')" class="">Leave</x-forms.form-button>
                    </form>
                    <hr class="mb-2">
                </li>
            @empty
                <p class="text-sm">No ongoing conversations currently.</p>
            @endforelse
        </ul>

    </div>
            <div class="mt-4">
            {{ $conversations->links() }}
        </div>
</x-settings.aside>
