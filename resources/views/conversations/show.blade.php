<x-layout>
<x-header />

<x-main>

    <div class="text-2xl flex-2 min-w-0 wrap-break-word mb-2 px-1">
        {{ $conversation->title }}
        {{-- {{ $messages->links() }} --}}
    </div>

    <livewire:livewire.user-search :conversation="$conversation" wire:key="user-search-{{ $conversation->id }}"/>

    <p class="pl-1">Only invited members can view or participate in this conversation.</p>

    <aside class="border w-full bg-linear-to-br from-gray-500 to-gray-300 text-black mb-2">
        <div class="w-full mb-2 bg-linear-to-tr from-gray-500 to-gray-200">
            <span class="pl-2 text-lg">Members:</span>
            <hr/>
        </div>
        <ul class="p-1 flex flex-wrap max-h-60 overflow-y-auto">
            @foreach ($conversation->users as $user)
                <li class="border m-0.5 p-1 flex gap-1 w-75">
                    <div class="w-24 h-24 max-sm:min-w-26  max-sm:min-h-26 max-sm:max-h-26 max-sm:max-w-26 overflow-hidden border shadow-xs shadow-black text-black">
                        <a href="{{ $user->user_url }}" class="w-full h-full">
                        <img src="{{ $user->profile_image_url }}"
                            class="w-full h-full object-cover"
                            alt="{{ $user->display_name ?? 'Deleted Member' }}'s profile image"
                            data-pin-nopin="true">
                        </a>
                    </div>
                    <div>
                        <a href="{{ $user->user_url }}"
                            class="inline w-fit h-fit hover:underline">
                            {{ $user->display_name }}
                        </a>
                        <p class="text-sm">Status: <span data-user-status="{{ $user->id }}">Checking... </span></p>
                    </div>
                </li>
            @endforeach
        </ul>
    </aside>

    <livewire:livewire.conversation.show :conversation="$conversation" wire:key="conversation-show-{{ $conversation->id }}"/>

    {{-- <div class="text-2xl flex-2 min-w-0 wrap-break-word mt-4">
        {{ $messages->links() }}
    </div> --}}

</x-main>

<x-footer />
</x-layout>
