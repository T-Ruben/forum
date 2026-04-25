<x-settings.aside>
    <x-slot:title>Created Threads</x-slot:title>
        <div class="mt-4">
            {{ $threads->links() }}
        </div>
        <div>
            <form action="{{ route('settings.threads') }}" method="GET" id="sortForm">
                <label for="sort">Order by: </label>
                <select name="sort" onchange="document.getElementById('sortForm').submit()"
                    class="text-black border rounded bg-gray-200 mb-4 cursor-pointer">
                    <option value="latest_activity" {{ $currentSort == 'latest_activity' ? 'selected' : '' }} class="cursor-pointer">
                        Recent Activity</option>
                    <option value="desc" {{ $currentSort == 'desc' ? 'selected' : '' }} class="cursor-pointer">
                        Recent Threads</option>
                    <option value="asc" {{ $currentSort == 'asc' ? 'selected' : '' }} class="cursor-pointer">
                        Oldest Threads</option>
                    <option value="most_posts" {{ $currentSort == 'desc' ? 'selected' : '' }} class="cursor-pointer">
                        Most Posts</option>
                </select>
            </form>
        </div>
    <div class=" border-l pl-2">

        <ul class="">
            @forelse ($threads as $thread)
                <li class="text-lg mb-2 ">
                    <div class="sm:float-left w-full pr-5">
                        <div class="min-w-0 flex-2 sm:flex gap-1">
                            <span class="block shrink-0">Thread name: </span>
                            <a href="{{ route('threads.show', [$thread->id, $thread->slug]) }}"
                                class="hover:underline active:underline duration-200 font-bold block min-w-0 truncate">
                                    <span class="truncate" title="{{ $thread->title }}">{{ $thread->title }}</span>
                            </a>
                        </div>
                        <div class="flex-col flex w-fit">
                            <span class="text-sm">Posts: {{ $thread->posts_count }}</span>
                        </div>
                    </div>
                    <form action="{{ route('threads.destroy', $thread->id) }}" method="POST" class="mb-2 flex sm:justify-end">
                        @csrf
                        @method('DELETE')

                        <x-forms.form-button onclick="return confirm('Are you sure? This action cannout be reversed.')"
                            title="Delete Thread"
                            alt="Delete thread. This action cannout be reversed."
                            :textSize="'text-sm'">
                            Delete
                        </x-forms.form-button>
                    </form>
                    <span class="text-sm flex sm:justify-end">This action cannout be reversed.</span>
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
