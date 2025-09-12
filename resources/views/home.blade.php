<x-layout>
        <x-header />

        <main class="text-gray-300">
            <div class="flex flex-row w-full h-full gap-2">
                <section class="p-4 flex flex-col justify-start bg-gray-500/75 w-4/6 rounded-t-md h-auto">

                    <h1 class="text-2xl py-2">Game Updates Forum</h1>

                    <div>
                            <div class="dark:bg-blue-950">
                                <h2 class="text-xl py-4 pl-4">Game Updates</h2>
                            </div>
                            <div>
                                <ul>
                                    @foreach ($forums as $forum)
                                        <li class=" text-xl font-medium">
                                            <a class="m-4 text-lg text-gray-300 hover:dark:text-blue-950/75 duration-300 hover:scale-101 inline-block"
                                            href="/forums/{{ $forum->slug }}">
                                            {{ $forum->title }}</a></li>
                                        <x-divide />
                                    @endforeach
                                </ul>
                            </div>
                    </div>

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
