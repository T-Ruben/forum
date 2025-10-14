<x-layout>
    <x-header />

    <x-main>

        <div class="flex justify-between">
            <h2 class="text-2xl mb-3">{{ $forum->title }}</h2>

            <a href="{{ route('threads.create', $forum->slug) }}">
                <span class="cursor-pointer rounded-md p-2 dark:bg-blue-950 hover:bg-blue-900/80 duration-200
                transition-colors">
                Post New Thread</span>
            </a>
        </div>


        <div>
            {{ $threads->links() }}
        </div>

        <ul class="">
            <li class=" dark:bg-blue-950 text-lg w-full h-auto">
                <div class="flex justify-between text-center items-center w-full">
                    <div class="hover:bg-blue-900/50 duration-200 transition-colors py-2 w-2/3">Title</div>
                    <div class="hover:bg-blue-900/50 duration-200 transition-colors py-2 w-1/6">Replies</div>
                    <div class="hover:bg-blue-900/50 duration-200 transition-colors py-2 w-1/6">Last Message</div>
                </div>
            </li>


            @foreach ($threads as $thread)
                    <li class="text-lg ml-2 flex items-center">
                        <div class="flex items-center w-2/3 min-w-0">
                            <div class="w-16 h-16 my-2 ml-1 mr-2">
                                <a href="{{ route('users.show', $thread->author) }}">
                                <img src="{{ $thread->author->profile_image_url }}"
                                    class="w-fit h-fit object-cover"
                                    alt="{{ $thread->author->display_name ?? 'Deleted Member' }}'s profile image">
                                </a>
                            </div>
                            <p class="truncate">
                                <span>
                                    <x-link :active="true" href="{{ route('threads.show', [$thread->id, $thread->slug]) }}">
                                        {{ $thread->title }}
                                    </x-link>
                                </span>
                                <span class="flex">
                                    <x-link :active="false" href="{{ route('users.show', $thread->author) }}">
                                            <span class="mr-1 text-gray-200">{{ $thread->author->display_name }}, </span>
                                    </x-link>
                                    <x-link :active="false" href="{{ route('threads.show', [$thread->id, $thread->slug]) }}">
                                        <span class="text-gray-300/75">{{ $thread->created_at->format('M d, Y') }}</span>
                                    </x-link>
                                </span>
                            </p>
                        </div>
                        <div class="flex items-center justify-center w-1/6 text-sm">Replies: {{ $thread->posts_count ?? '0' }}</div>
                        <div class="flex flex-col justify-center items-start w-1/6 text-sm">
                            @if ($thread->latestPost?->author)
                                <a href="{{ route('users.show', $thread->latestPost->author) }}">
                                    <span class="block">{{ $thread->latestPost->author->display_name }}</span>
                                </a>
                                <span class="block">{{ $thread->latestPost?->created_at->diffForHumans() ?? 'No Activity' }}</span>
                            @else
                                <span class="block">No Activity</span>
                            @endif
                        </div>

                    </li>
                    <x-divide />
            @endforeach
        </ul>
        <div>
            {{ $threads->links() }}
        </div>
    </x-main>

    <x-footer />

</x-layout>
