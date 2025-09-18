<x-layout>

    <x-header />

    <x-main>
        <div class="text-xl">
            {{ $thread->title }}
        </div>
        <x-divide />
        <div>
            @foreach ($thread->posts as $post)
                <x-post :post="$post" />
            @endforeach
        </div>

    </x-main>

    <x-footer />

</x-layout>
