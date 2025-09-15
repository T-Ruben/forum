<x-layout>
    <x-header />

    <x-main>

        <h2 class="text-2xl">{{ $forum->title }}</h2>
        <x-divide />
        <ul>
            @foreach ($forum->threads as $thread)
                    <li class="text-lg ">
                        <x-link href="/threads/{{ $thread->slug }}">{{ $thread->title }}</x-link>
                    </li>
            @endforeach
        </ul>


    </x-main>

    <x-footer />

</x-layout>
