<x-layout>
        <x-header />

        <main class="text-gray-300">
            <div class="flex flex-row w-full h-full gap-2">
                <section class="p-4 flex flex-col justify-start bg-gray-500/75 w-4/6 rounded-t-md h-auto">

                    <h1 class="text-2xl py-2">Game Updates Forum</h1>

                    <section>

                    @foreach ($forumsCategory as $category)
                            <div class="dark:bg-blue-950">
                                <h2 class="text-xl py-4 pl-4">{{ $category->title }}</h2>
                            </div>
                        <div>
                            <ul>
                                @foreach ($category->forums as $forum)
                                    <li class="text-xl">
                                        <x-link href="/forums/{{ $forum->slug }}">{{ $forum->title }}</x-link>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach

                    </section>

                </section>

                <section class="p-2 flex bg-gray-500/75 w-2/6 rounded-t-md h-auto">
                    test
                </section>
            </div>
            </main>
            {{-- Footer here to be made --}}
            <x-footer>
                Test
            </x-footer>


</x-layout>
