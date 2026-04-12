<x-layout>
    <x-header />
        <main>
        <span class="my-2 flex justify-center">{{ $following->links() }}</span>
            <div>
                <ul class="flex flex-wrap h-full justify-center gap-2 mb-2">
                    @forelse ($following as $followingUsers)
                        <li class="border p-2 w-90 h-45 ring-blue-500 ring inset-shadow-blue-500 flex gap-2">
                            <div class="w-4/8 h-2/2 shrink-0">
                                <a href="{{ route('users.show', $followingUsers) }}">
                                    <img src="{{ asset($followingUsers->profile_image_url) }}"
                                        alt="{{ $followingUsers->display_name }}"
                                        class="w-fit h-full object-cover ring-indigo-700 ring"
                                        data-pin-nopin="true">
                                </a>
                            </div>
                            <ol class="w-4/8 h-2/2 flex flex-col">
                                <li>Name: <span class="text-indigo-200">
                                    <a href="{{ route('users.show', $followingUsers) }}" class="hover:underline">
                                        {{ $followingUsers->name }}
                                    </a>
                                </span></li>
                                <li>Member since: <span class="text-indigo-200">{{ $followingUsers->created_at->diffForHumans() }}</span></li>
                                <li>Following: <span class="text-indigo-200">{{ $followingUsers->following_count }}</span></li>
                                <li>Followers: <span class="text-indigo-200">{{ $followingUsers->followers_count }}</span></li>
                                <li>Messages: <span class="text-indigo-200">{{ $followingUsers->posts_count }}</span></li>
                            </ol>
                        </li>
                        @empty
                        <p>This user isn't following anyone yet.</p>
                    @endforelse
                </ul>
            </div>
        </main>
        <span class="my-2 flex justify-center">{{ $following->links() }}</span>
    <x-footer />
</x-layout>
