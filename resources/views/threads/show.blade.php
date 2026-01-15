<x-layout>

    <x-header />

    <x-main>
        <div class="text-2xl flex-2 min-w-0 wrap-break-word">
            {{ $thread->title }}
            {{ $posts->links() }}
        </div>
        <hr class="border-gray-500 my-2">
        <div>
            @foreach ($posts as $post)
                <x-post :post="$post" :thread="$thread" />
            @endforeach
        </div>

        <div class="flex border border-black min-h-[200px] h-auto w-full max-w-full bg-gray-200/85">

            <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black m-2 text-black max-sm:hidden">
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


                <form action="{{ route('posts.store') }}" method="POST" class="formReload h-full" id="postForm">
                    @csrf

                    <input type="hidden" name="thread_id" value="{{ $thread->id }}">

                    <x-editor />

                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        class="w-full p-2 bg-gray-200 text-black resize-none overflow-hidden border border-gray-600
                        outline-none"
                        placeholder="Write your post...">{{ old('content') }}</textarea>

                    <div class="my-auto block">
                        @error('content')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="text-white dark:bg-blue-950 hover:dark:bg-blue-900/80 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
                        Post Reply
                    </button>

                </form>
            </div>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>

    </x-main>

    <x-footer />
</x-layout>
