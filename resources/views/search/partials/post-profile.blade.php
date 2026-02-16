<div class="flex justify-between w-full truncate">
    <a href="{{ route('users.show', [$post->profile_user_id, 'page' => $post->getPageNumberProfile()]) }}#post-{{ $post->id }}"
        class="search-results hover:underline text-lg font-bold truncate">
        {{ $post->content }}
    </a>
    <span class="text-gray-400 w-fit">
        Profile Post
    </span>
</div>

<div class="search-results flex justify-between text-sm">
    <div class="line-clamp-3">
        <p class="text-white">
            {{ $post->content }}
        </p>
        <p class=" text-gray-300">
            Post by: {{ $post->user->display_name }}, <x-time-display :time="$post->created_at" />
        </p>
    </div>
</div>
