<x-layout>
    <x-header />

    <x-main>

        <h2 class="text-xl">{{ $forum->title }}</h2>
        <x-divide />
        @foreach ($forum->threads as $thread)
            {{ $thread->title }}
        @endforeach


    </x-main>

    <x-footer />

</x-layout>
