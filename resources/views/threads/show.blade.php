<x-layout>

    <x-header />

    <x-main>
        <div class="text-2xl flex-2 min-w-0 wrap-break-word">
            {{ $thread->title }}
            {{ $posts->links() }}
        </div>
        <x-divide />
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
                    <input type="hidden" name="parent_id" value="{{ $post->id ?? null }}">

                    <div id="editor" class="border text-black bg-gray-100 mb-1">
                        <div id="toolbar-container">
                            <span class="ql-formats">
                                <select class="ql-font"></select>
                                <select class="ql-size"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                                <button class="ql-strike"></button>
                            </span>
                            <span class="ql-formats">
                                <select class="ql-color"></select>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-list" value="ordered"></button>
                                <button class="ql-list" value="bullet"></button>
                                <button class="ql-indent" value="-1"></button>
                                <button class="ql-indent" value="+1"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-link"></button>
                                <button class="ql-image"></button>
                                <button class="ql-video"></button>
                            </span>

                            <span class="ql-formats">
                                <button class="ql-clean"></button>
                            </span>
                        </div>
                        <div id="editor-container" class="min-h-42 w-full p-2 bg-white text-black border rounded"></div>
                    </div>

                    <input type="hidden" id="content" name="content">


                    <div class="my-auto block">
                        @error('content')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="text-white dark:bg-blue-900 hover:dark:bg-blue-900/75 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
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
