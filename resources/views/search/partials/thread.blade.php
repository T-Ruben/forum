<div class="flex justify-between w-full truncate">
    <a href="{{ route('threads.show', [$thread, $thread->slug]) }}" class="search-results hover:underline text-lg font-bold">
        {{ $thread->title }}
    </a>
    <span class="text-gray-400">
        Thread
    </span>
</div>
<div class="flex justify-between">
    <p class="text-gray-300 text-sm">Thread by: <a href="{{ $thread->user?->user_url }}"
            class="hover:underline">
        {{ $thread->user->display_name }},
    </a>
    Created at: <x-time-display :time="$thread->created_at" />,
    In forum:
    <a href="{{ route('forums.show', $thread->forum) }}"
            class="hover:underline">
        {{ $thread->forum->title }}
    </a>
    </p>

</div>
