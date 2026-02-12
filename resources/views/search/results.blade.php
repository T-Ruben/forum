<x-layout>
    <x-header />
    <x-main>
        <span class="mb-2">{{ $threads->links() }}</span>
        <section>
            <p class="text-2xl">Search results for query: <span class="font-bold text-gray-200">{{ $query }}</span></p>
            <ul class="">
                @if($threads->count() >= 1)
                    @foreach ($threads as $thread)
                        <li class="border p-1 my-1">
                            <a href="{{ route('threads.show', [$thread, $thread->slug]) }}" class="search-results hover:underline text-lg">
                                {{ $thread->title }}
                            </a>
                            <div class="flex justify-between">
                                <p class="text-gray-300 text-sm">Thread by: <a href="{{ $thread->user?->user_url }}"
                                        class="hover:underline">
                                    {{ $thread->user->display_name }},
                                </a>
                                Created at: <x-time-display :time="$thread->created_at" />,
                                In forum: {{ $thread->forum->title }}
                                </p>
                                <span class="text-gray-400">
                                    Thread
                                </span>
                            </div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </section>
        <span class="my-2">{{ $threads->links() }}</span>
    </x-main>
    <x-footer/>
</x-layout>

<script src="https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchTerm = urlParams.get('query');

    if(searchTerm) {
        const instance = new Mark(document.querySelectorAll(".search-results"));

        instance.mark(searchTerm, {
            "element": "mark",
            "className": "highlight",
            "accuracy": "partially",
            "separateWordSearch": true
        })
    }
})

</script>
