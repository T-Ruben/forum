<!-- Changes:
Took from first section: bg-gray-500/75
Took from second section: bg-gray-500/75
-->


<x-layout>

    <x-header />

    <main>
        <div class="flex flex-row w-full h-full gap-2">
            <section class="p-4 flex flex-col justify-start w-9/12 rounded-t-md h-auto max-lg:w-6/6">

                <h1 class="text-2xl py-2">Game Updates Forum</h1>

                <section>
                    @foreach ($forumsCategory as $category)
                        <div class="dark:bg-blue-950 px-4 py-2">
                            <h2 class="text-xl">{{ $category->title }}</h2>
                        </div>
                        <ul class="">

                            @foreach ($category->forums as $forum)
                                <li class="flex justify-between items-center py-1 px-4">
                                    <div class="flex my-2 mr-3 w-12 h-12">
                                        @include('icons.chat')
                                    </div>

                                    <div class="w-4/5">
                                        <x-link :active="true" href="{{ route('forums.show', $forum->slug) }}"
                                            title="{{ $forum->title }}">
                                            {{ $forum->title }}
                                        </x-link>

                                        <div class="sm:flex gap-2 flex-2 mt-1 text-sm text-gray-300/75">
                                            <div class="flex gap-1">
                                                <span>Discussions:</span>
                                                <span class="text-gray-200">{{ $forum->threads_count }}</span>
                                            </div>
                                            <div class="flex gap-1">
                                                <span>Messages:</span>
                                                <span class="text-gray-200">{{ $forum->posts_count }}</span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="w-2/5 text-left truncate text-sm text-gray-300/75 md:block hidden">
                                        <span class="text-gray-200">
                                            <span class="float-left mr-1">Latest:</span>
                                            @if ($forum->latestThread?->id)
                                                <a href="{{ route('threads.show', [$forum->latestThread->id, $forum->latestThread->slug]) }}"
                                                aria-current="{{ $forum->latestThread?->title }}"
                                                title="{{ $forum->latestThread?->title }}"
                                                class="flex-grow truncate block text-white hover:underline min-w-0">
                                                {{ $forum->latestThread?->title }}</a>
                                                @else
                                                 <span class="text-gray-400 italic">No activity yet</span>
                                            @endif

                                        </span>
                                        <span class="block">
                                            <span class="text-gray-200">
                                                <x-user-link :user="$forum->latestThread?->latestPost?->author" />
                                            </span>
                                            {{-- <span class="post-time" data-time="{{ $forum->latestThread?->latestPost->updated_at->toIso8601String() }}">
                                                {{ $forum->latestThread?->latestPost?->created_at->diffForHumans() ?? '' }}
                                                at {{ $forum->latestThread?->latestPost?->created_at->format('H:i A') }}
                                            </span> --}}
                                                <x-time-display :time="$forum?->latestThread?->latestPost?->updated_at"/>
                                        </span>
                                    </div>
                                </li>
                                <hr class="border-gray-500 my-2">

                            @endforeach
                        </ul>
                    @endforeach

                </section>
            </section>

            <section class="p-2 w-3/12 rounded-t-md h-auto max-lg:hidden">
                <div class="flex w-full mb-4">
                    <div class="w-fit h-fit mr-2 border-2 border-double p-0.5">
                        @auth
                            <a href="{{ route('users.show', auth()->user()->id) }}">
                                <img src="{{ asset(auth()->user()->profile_image_url) }}" class="w-32 h-32 object-cover"
                                    alt="{{ auth()->user()->name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                            </a>
                        @endauth

                        @guest
                            <a href="#">
                                <img src="{{ asset('images/default-avatar.png') }}" class="w-32 h-32 object-cover"
                                    alt="Guest's profile image" data-pin-nopin="true">
                            </a>
                        @endguest
                    </div>
                    <div class="w-1/2">
                        <ul>
                            <li class="mb-2">
                                <p class="font-bold">
                                    @auth
                                        <a href="{{ route('users.show', auth()->user()->id) }}"
                                            class="hover:underline">
                                            {{ auth()->user()->name }}
                                        </a>
                                    @endauth
                                    @guest
                                        Guest
                                    @endguest
                                </p>
                            </li>
                            <li>
                                @auth
                                    <p class="text-sm">Messages: {{ auth()->user()->posts->count() }}</p>
                                @endauth
                            </li>
                        </ul>
                        <hr/>
                    </div>
                </div>

{{-- New Posts Aside --}}
                <aside class="mb-5">
                    <div class="mb-2">
                        <ul class="flex gap-2 justify-center h-10 dark:bg-blue-950 text-lg pl-1">
                            <li class="flex hover:dark:bg-blue-900/75 items-center cursor-pointer px-1">New Posts</li>
                        </ul>
                    </div>

                    <div class="min-w-0 flex flex-col w-full truncate">
                        <ul class="">
                            @foreach ($forumPosts as $post)
                                <li class="mb-2 flex">
                                    <div class="w-16 h-16 mr-2 flex shrink-0 border">
                                        <a href="{{ $post->author?->author_url }}">
                                            <img src="{{ asset($post->author->profile_image_url) }}"
                                            class="object-cover w-full h-full" alt="" data-pin-nopin="true">
                                        </a>
                                    </div>
                                    <div class="w-full truncate">
                                        <h3 class="font-semibold flex-grow truncate block break-words">
                                            <a href="{{ route('threads.show', [$post->thread, $post->thread->id]) }}"
                                                class="hover:underline duration-200">
                                                {{ $post->thread->title }}
                                            </a>
                                        </h3>
                                        <p class="truncate">Latest: <a href="{{ $post->author?->author_url }}"
                                            class="hover:underline duration-200">
                                                {{ $post->author->display_name }}
                                            </a>,
                                            <x-time-display :time="$post->updated_at"/>
                                        </p>
                                        <p><a href="{{ route('forums.show', $post->thread->forum->slug) }}"
                                            class="hover:underline duration-200">
                                            {{ $post->thread->forum->title }}</a></p>
                                        <hr/>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

{{-- New Profile Posts Aside --}}
                <aside>
                    <div class="mb-2">
                        <ul class="flex gap-2 justify-center h-10 dark:bg-blue-950 text-lg pl-1">
                            <li class="flex hover:dark:bg-blue-900/75 items-center cursor-pointer px-1">New Profile Posts</li>
                        </ul>
                    </div>

                    <div class="min-w-0 flex flex-col w-full">
                        <ul>
                            @foreach ($profilePosts as $post)
                                <li class="mb-2 flex">
                                    <div class="w-16 h-16 mr-2 flex shrink-0 border">
                                        <a href="{{ $post->author?->author_url }}">
                                            <img src="{{ asset($post->author->profile_image_url) }}"
                                            class="object-cover w-full h-full" alt="" data-pin-nopin="true">
                                        </a>
                                    </div>
                                    <div class="w-full overflow-hidden">
                                        <div class="font-semibold flex">
                                            <a href="{{ $post->author?->author_url }}"
                                                class="hover:underline duration-200">
                                                <h3 class="truncate">{{ $post->author->display_name }}</h3>
                                            </a>
                                            {{-- @if ($post->profileOwner->id !== $post->author->id)
                                                @include('icons.arrow-right')
                                                <a href="{{ route('users.show', $post->profile_user_id) }}"
                                                    class="hover:underline duration-200">
                                                    {{ $post->profileOwner->name }}</a>

                                            @endif --}}
                                            @if ($post->profile_user_id !== $post->author->id)
                                                @include('icons.arrow-right')
                                                <a href="{{ $post->profileOwner?->author_url }}"
                                                    class="hover:underline duration-200">
                                                    <span class="">{{ $post->profileOwner->display_name }}</span></a>

                                            @endif
                                        </div>
                                        <div class="text-white break-words line-clamp-5">
                                            {{ trim($post->content) }}
                                        </div>

                                        <x-time-display :time="$post->updated_at"/>

                                        <hr/>
                                    </div>

                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </section>
        </div>
    </main>
    {{-- Footer here to be made --}}
    <x-footer>
        Test
    </x-footer>


</x-layout>
