<x-layout>
    <x-header />

    <x-main>

        <div class="flex justify-between">
            <h2 class="text-2xl mb-3">{{ $forum->title }}</h2>
            @if ($forum->forumCategory->is_admin_only)
                @if (auth()->user()->is_admin)
                    <a href="{{ route('threads.create', $forum->slug) }}">
                        <span class="cursor-pointer rounded-md p-2 dark:bg-blue-950 hover:bg-blue-900/80 duration-200
                        transition-colors">
                        Post New Thread</span>
                    </a>
                @endif
            @else
                <a href="{{ route('threads.create', $forum->slug) }}">
                    <span class="cursor-pointer rounded-md p-2 dark:bg-blue-950 hover:bg-blue-900/80 duration-200
                    transition-colors">
                    Post New Thread</span>
                </a>
            @endif

        </div>

        <livewire:livewire.forum-show :forum="$forum" />

    </x-main>

    <x-footer />

</x-layout>


