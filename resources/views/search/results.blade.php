<x-layout>
    <x-header />
    <x-main>
        <span class="mb-2">{{ $results->links() }}</span>
        <section>
            <p class="text-2xl">Search results for query: <span class="font-bold text-gray-200">{{ $query }}</span></p>
            <ul class="">
                    @foreach ($results as $result)
                        <li class="border p-1 my-1">
                            @switch($result['type'])
                                @case('thread')
                                    @include('search.partials.thread', ['thread' => $result['model']])
                                    @break

                                @case('post_thread')
                                    @include('search.partials.post-thread', ['post' => $result['model']])
                                @break

                                @case('post_profile')
                                    @include('search.partials.post-profile', ['post' => $result['model']])
                                @break

                                @case('post_reply')
                                    @include('search.partials.post-reply', ['post' => $result['model']])
                                @break

                            @endswitch
                        </li>
                    @endforeach
            </ul>
        </section>
        <span class="my-2">{{ $results->links() }}</span>
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
            "separateWordSearch": true,
            "acrossElements": true,
            "diacritics": true,
        });
    }
});

</script>
