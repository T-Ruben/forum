<div class="flex justify-between w-full">
    <a href="{{ route('threads.show', [$post->thread, $post->thread->slug, 'page' => $post->getPageNumber()]) }}#post-{{ $post->id }}"
        class="search-results hover:underline text-lg font-bold">
        {{ $post->thread->title }}
    </a>
    <span class="text-gray-400">
        Thread Post
</span>
</div>

<div class="search-results flex justify-between text-sm">
    <div class="truncate">
        <p class="truncate text-white">
            {{ $post->content }}
        </p>
        <p class=" text-gray-300">
            Post by: {{ $post->user->display_name }}, <x-time-display :time="$post->created_at" />
        </p>
    </div>
</div>
