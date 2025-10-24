<x-layout>

    <x-header />

    <x-main>
        <div class="text-xl flex-2 min-w-0 wrap-break-word">
            {{ $thread->title }}
            {{ $posts->links() }}
        </div>
        <x-divide />
        <div>
            @foreach ($posts as $post)
                <x-post :post="$post" :thread="$thread" />
            @endforeach
        </div>

        <div class="flex border border-black min-h-[200px] h-auto w-full max-w-full bg-gray-200/75">

            <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black m-2 text-black">
                @foreach ($posts as $post)
                    @auth
                        <a href="{{ route('users.show', auth()->user()->id) }}">
                            <img src="{{ asset(auth()->user()->profile_image_url) }}" class="w-32 h-32 object-cover"
                                alt="{{ auth()->user()->name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                        </a>
                    @endauth

                    @guest
                        <a href="#">
                            <img src="{{ asset('images/default-avatar.png') }}" class="w-32 h-32 object-cover"
                                alt="Guest's profile image" data-pin-nopin="true">
                        </a>
                    @endguest
                @endforeach
            </div>

            <div class="flex-2 flex-col h-2/3 m-2">

                <form action="{{ route('posts.store') }}" method="POST" class="formReload h-full">
                    @csrf

                    <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                    <input type="hidden" name="parent_id" value="{{ $post->id ?? null }}">


                    <textarea class="h-full w-full resize-none outline-0 border-1 p-1 text-black bg-gray-100" name="content" id="content"
                        placeholder="Write your reply...">{{ old('content') }}</textarea>

                    <button type="submit"
                        class="text-white dark:bg-blue-900 hover:dark:bg-blue-900/75 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
                        Post Reply
                    </button>

                </form>
                <div class="my-auto block">
                    @error('content')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>


        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>

    </x-main>

    <x-footer />
</x-layout>
