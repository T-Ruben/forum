<section class="my-2 border p-2 flex bg-gray-300/75 text-black">

    <div class="h-full py-3 bg-gray-400/25 border-gray-400 border shadow-sm shadow-black/50 float-left text-left flex flex-col items-center justify-center w-42">
        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black">
            <img src="{{ $post->user->getProfileImageUrl() }}"
            class="w-32 h-32 object-cover"
            alt="{{ $post->user->name }}'s profile image">
        </div>
        <div class="flex flex-col w-full px-2">
            <div class="pt-2">
                {{ $post->user->name }}
            </div>
            <div>
                {{ $post->user->role }}
            </div>
            <x-divide />
            <div class="flex justify-between">
                <p class="text-sm"><span class="text-gray-950/75">Joined: </span></p><p>{{ $post->user->created_at->format('M d, Y') }}</p>

            </div>
            <div class="flex justify-between">
                <p class="text-sm"><span class="text-gray-950/75">Messages: </span></p><p>{{ $post->user->posts_count }}</p>
            </div>
        </div>
    </div>

    <div>

    </div>

    <div>

    </div>
</section>
