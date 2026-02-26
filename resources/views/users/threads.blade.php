<x-settings.aside>
    <x-slot:title>Created Threads</x-slot:title>
        <div class="mt-4">
            {{ $threads->links() }}
        </div>
    <div class=" border-l pl-2">

        <ul class="">
            @forelse ($threads as $thread)
                <li class="text-lg mb-2 ">
                    <div>
                        <div class="min-w-0 flex gap-1">
                            <span class=" shrink-0">Thread name: </span>
                            <a href="{{ route('threads.show', [$thread->id, $thread->slug]) }}"
                                class="hover:underline duration-200 font-bold block min-w-0 truncate">
                                    <span class="truncate">{{ $thread->title }}</span>
                            </a>
                        </div>
                        <div>
                            <span class="text-sm">Posts: {{ $thread->posts->count() }}</span>
                        </div>
                    </div>
                    <form action="{{ route('threads.destroy', $thread->id) }}" method="POST" class="mb-2 flex justify-end">
                        @csrf
                        @method('DELETE')

                        <x-forms.form-button onclick="return confirm('Are you sure? This action cannout be reversed.')"
                            title="Delete Thread"
                            alt="Delete thread. This action cannout be reversed.">
                            Delete
                        </x-forms.form-button>
                    </form>
                    <span class="text-sm flex justify-end">This action cannout be reversed.</span>
                    <hr class="mb-2">
                </li>
                @empty
                    <p class="text-sm">No created threads currently.</p>
            @endforelse
        </ul>

    </div>
            <div class="mt-4">
            {{ $threads->links() }}
        </div>
</x-settings.aside>
