<x-layout>
    <x-header />

    <x-main>

        <div class="flex justify-between items-center my-2 mx-1">
            <h2 class="text-xl sm:text-2xl">{{ $forum->title }}</h2>
            @if ($forum->forumCategory->is_admin_only)
                @if (auth()->user()?->is_admin)
                    <a href="{{ route('threads.create', $forum->slug) }}" class="shrink-0">
                        <span class="cursor-pointer rounded-md p-2 dark:bg-blue-950 hover:bg-blue-900/80 active:bg-blue-900/80
                        duration-200 transition-colors text-sm">
                        Post New Thread</span>
                    </a>
                @endif
            @else
                <a href="{{ route('threads.create', $forum->slug) }}" class="shrink-0">
                    <span class="cursor-pointer rounded-md p-2 dark:bg-blue-950 hover:bg-blue-900/80 active:bg-blue-900/80
                        duration-200 transition-colors text-sm">
                    Post New Thread</span>
                </a>
            @endif

        </div>

        <livewire:livewire.forum-show :forum="$forum" />

    </x-main>

    <x-footer />

</x-layout>


