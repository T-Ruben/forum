<x-layout>
    <x-header />
        <main>
        <div class="flex justify-between mt-2 h-10 align-middle">
            <div>
                <form action="{{ route('members.index') }}" method="GET" id="sortForm">
                    <label for="sort">Order by: </label>
                    <select name="sort" onchange="document.getElementById('sortForm').submit()"
                    class="text-black border rounded bg-gray-200 mb-4 cursor-pointer">
                        <option value="newest" {{ $currentSort == 'newest' ? 'selected' : '' }}>Newest Members</option>
                        <option value="oldest" {{ $currentSort == 'oldest' ? 'selected' : '' }}>Oldest Members</option>
                        <option value="name_asc" {{ $currentSort == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                    </select>
                </form>
            </div>
            <span>{{ $users->links() }}</span>
        </div>
        <hr class="my-5 h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-50" />
        <div>
            <ul class="flex flex-wrap h-full justify-center gap-2 mb-2">
                @foreach ($users as $user)
                    <li class="border p-2 w-90 h-45 ring-blue-500 ring inset-shadow-blue-500 flex gap-2">
                        <div class="w-4/8 h-2/2">
                            <a href="{{ route('users.show', $user) }}">
                                <img src="{{ asset($user->profile_image_url) }}"
                                    alt="{{ $user->display_name }}"
                                    class="w-fit h-full object-cover ring-indigo-700 ring"
                                    data-pin-nopin="true">
                            </a>
                        </div>
                        <ol class="w-4/8 h-2/2 flex flex-col">
                            <li>Name: <span class="text-indigo-200">
                                <a href="{{ route('users.show', $user) }}" class="hover:underline">
                                    {{ $user->display_name }}
                                </a>
                            </span></li>
                            <li>Member since: <span class="text-indigo-200">{{ $user->created_at->diffForHumans() }}</span></li>
                            <li>Following: <span class="text-indigo-200">{{ $user->following_count }}</span></li>
                            <li>Followers: <span class="text-indigo-200">{{ $user->followers_count }}</span></li>
                            <li>Messages: <span class="text-indigo-200">{{ $user->posts_count }}</span></li>
                        </ol>
                    </li>
                @endforeach
            </ul>
        </div>a
        </main>
        <hr class="my-5 h-px border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-400 to-transparent opacity-50" />
        <span class="my-2 flex justify-end">{{ $users->links() }}</span>
    <x-footer />
</x-layout>
