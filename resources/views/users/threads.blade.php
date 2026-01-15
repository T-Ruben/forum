<x-settings.aside>
    <x-slot:title>Created Threads</x-slot:title>

    <div>
        <div class="mt-4">
            {{ $threads->links() }}
        </div>
        <ul class="">
            @foreach ($threads as $thread)
                <li class="text-lg mb-2">
                    <a href="{{ route('threads.show', [$thread->id, $thread->slug]) }}" class="hover:underline duration-200">
                        {{ $thread->title }}
                    </a>
                    <form action="{{ route('threads.destroy', $thread->id) }}" method="POST" class="mb-2">
                        @csrf
                        @method('DELETE')

                        <x-forms.form-button onclick="return confirm('Are you sure?')">Delete</x-forms.form-button>
                    </form>
                    <hr class="mb-2">
                </li>
            @endforeach
        </ul>
    </div>
</x-settings.aside>
