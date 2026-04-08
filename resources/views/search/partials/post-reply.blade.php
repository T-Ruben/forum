<div class="flex">
    <div>
        <a href="{{ $post->user?->user_url }}" class="h-full w-full">
            <img src="{{ $post->user?->profile_image_url }}"
                alt="{{ $post->user?->display_name }}"
                class="w-12 h-12 object-cover border">
        </a>
    </div>
    <div class="flex-2 ml-2">
        <div class="flex justify-between w-full truncate">
            <a href="{{ route('users.show', [$post->profileOwner, 'page' => $post->getPageNumberProfile(), 'highlight' => $post->id]) }}#post-{{ $post->id }}"
                class="search-results hover:underline text-lg font-bold truncate">
                {{ $post->content }}
            </a>
            <span class="text-gray-400 w-fit">
                Profile Comment
            </span>
        </div>

        <div class="search-results flex justify-between text-sm">
            <div class="min-w-0 flex-1">
                <p class="text-white line-clamp-3 break-all">
                    {{ $post->content }}
                </p>
                <p class=" text-gray-300">
                    Post by: <a href="{{ $post->user->user_url }}" class="hover:underline">{{ $post->user->display_name }}</a>,
                    <x-time-display :time="$post->created_at" />
                </p>
            </div>
        </div>
    </div>
</div>
