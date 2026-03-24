<x-settings.aside>
    <x-slot:title>Conversations</x-slot:title>
        <div class="mt-4">
            {{ $conversations->links() }}
        </div>
        <div>
            <form action="{{ route('settings.conversations') }}" method="GET" id="sortForm">
                <label for="sort">Order by: </label>
                <select name="sort" onchange="document.getElementById('sortForm').submit()"
                    class="text-black border rounded bg-gray-200 mb-4 cursor-pointer">
                    <option value="latest_activity" {{ $currentSort == 'latest_activity' ? 'selected' : '' }} class="cursor-pointer">
                        Recent Activity</option>
                    <option value="desc" {{ $currentSort == 'desc' ? 'selected' : '' }} class="cursor-pointer">
                        Recent Conversation</option>
                    <option value="asc" {{ $currentSort == 'asc' ? 'selected' : '' }} class="cursor-pointer">
                        Oldest Conversation</option>
                    <option value="most_messages" {{ $currentSort == 'desc' ? 'selected' : '' }} class="cursor-pointer">
                        Most Messages</option>
                    <option value="most_members" {{ $currentSort == 'desc' ? 'selected' : '' }} class="cursor-pointer">
                        Most Members</option>
                </select>
            </form>
        </div>
    <div class=" border-l pl-2">

        <ul class="">
            @forelse ($conversations as $conversation)
                <li class="text-lg mb-2 ">
                    <div class="float-left">
                        <div class="min-w-0 flex gap-1">
                            <span class=" shrink-0">Name: </span>
                            <a href="{{ route('conversation.show', ['conversation' => $conversation]) }}"
                                class="hover:underline duration-200 font-bold block min-w-0 truncate">
                                    <span class="truncate" title="{{ $conversation->title }}">{{ $conversation->title }}</span>
                            </a>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm">Messages: {{ $conversation->messages_count }}</span>
                            <span class="text-sm">Members: {{ $conversation->users_count }}</span>
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
