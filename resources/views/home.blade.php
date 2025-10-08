<x-layout>
    <x-header />

    <main>
        <div class="flex flex-row w-full h-full gap-2">
            <section class="p-4 flex flex-col justify-start bg-gray-500/75 w-4/6 rounded-t-md h-auto max-lg:w-6/6">

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
                                        <x-link :active="true" href="/forums/{{ $forum->slug }}"
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


                                    <div class="w-2/5 text-left text-sm text-gray-300/75 md:block hidden">
                                        <span class="text-gray-200">
                                            <span class="float-left mr-1">Latest:</span>
                                            <a href="/threads/{{ $forum->latestThread?->id }}/{{ $forum->latestThread?->slug }}"
                                                aria-current="{{ $forum->latestThread?->title }}"
                                                title="{{ $forum->latestThread?->title }}"
                                                class="flex-grow truncate block text-white hover:underline min-w-0">
                                                {{ $forum->latestThread?->title ?? 'No threads yet' }}</a>
                                        </span>
                                        <span class="block">
                                            <span class="text-gray-200">
                                                {{-- POSSIBLE PROBLEM HERE, CHECK PROPERLY AFTER CRUD. NAME MIGHT NOT BE RIGHT --}}
                                                {{ $forum->latestThread?->latestPost?->author->display_name ?? 'No post yet' }},
                                            </span>
                                            <span>
                                                {{ $forum->latestThread?->latestPost?->created_at->diffForHumans() ?? '' }}
                                            </span>
                                        </span>
                                    </div>
                                </li>
                                <x-divide />
                            @endforeach
                        </ul>
                    @endforeach

                </section>
            </section>

            <section class="p-2 bg-gray-500/75 w-2/6 rounded-t-md h-auto hidden lg:flex">
                test
            </section>
        </div>
    </main>
    {{-- Footer here to be made --}}
    <x-footer>
        Test
    </x-footer>


</x-layout>
