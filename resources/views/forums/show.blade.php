<x-layout>
    <x-header />

    <x-main>

        <h2 class="text-2xl mb-3">{{ $forum->title }}</h2>

        <div>
            {{ $threads->links() }}
        </div>


        <div class="dark:bg-blue-950 w-full h-10">

        </div>
        <ul class="">
            @foreach ($threads as $thread)
                    <li class="text-lg ml-2">
                        <p>
                            <span>
                                <x-link :active="true" href="/threads/{{ $thread->id }}/{{ $thread->slug }}">{{ $thread->title }}</x-link>
                            </span>
                            <span class="flex">
                                <x-link :active="false" href="#">
                                        <span class="mr-1">{{ $thread->author->name }}, </span>
                                </x-link>
                                <x-link :active="false" href="/threads/{{ $thread->id }}/{{ $thread->slug }}">
                                    {{ $thread->created_at->format('M d, Y') }}
                                </x-link>
                            </span>
                        </p>
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
