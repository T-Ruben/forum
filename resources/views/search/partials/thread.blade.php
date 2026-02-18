<div class="flex">
    <div class="">
        <a href="{{ $thread->user->user_url }}" class="h-full w-full">
            <img src="{{ $thread->user->profile_image_url }}"
                alt="{{ $thread->user->display_name }}"
                class="w-12 h-12 object-cover border">
        </a>
    </div>
    <div class="flex-2 ml-2 min-w-0">
        <div class="flex justify-between">
            <a href="{{ route('threads.show', [$thread, $thread->slug]) }}"
                class="search-results hover:underline text-lg font-bold truncate">
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
    </div>
</div>
