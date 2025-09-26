<x-layout>
    <x-header />

    <x-main>

        <h2 class="text-2xl">{{ $forum->title }}</h2>
        <x-divide />
        <ul class="">
            @foreach ($forum->threads as $thread)
                    <li class="text-lg ml-2">
                        <p>
                            <span>
                                <x-link :active="true" href="/threads/{{ $thread->slug }}">{{ $thread->title }}</x-link>
                            </span>
                            <span class="flex">
                                <x-link :active="false" href="#">
                                    @if ($thread->user)
                                        <span class="mr-1">{{ $thread->user->name }}, </span>
                                        @else
                                        <span class="mr-1">User Deleted, </span>
                                    @endif
                                </x-link>
                                <x-link :active="false" href="/threads/{{ $thread->slug }}">
                                    {{ $thread->created_at->format('M d, Y') }}
                                </x-link>
                            </span>
                        </p>
                    </li>
                    <x-divide />
            @endforeach
        </ul>

    </x-main>

    <x-footer />

</x-layout>
