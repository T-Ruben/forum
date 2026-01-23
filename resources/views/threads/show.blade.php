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

            <div class="flex-2 flex-col h-2/3 m-2 min-w-0">

                <div class="">
                    @if ($replyTo)
                        <div class="mb-4 p-3 border rounded text-sm border-gray-600 text-black">
                            <p class="flex justify-between border-b">
                                <span>Replying to <a href="#post-{{ $replyTo->id }}"
                                    class="hover:underline font-semibold duration-200">{{ $replyTo->author->name }}</a></span>
                                <a href="{{ route('threads.show', [$thread->id, $thread->slug, 'page' => request('page')]) }}"
                                    class="formReload hover:text-red-500/75 duration-200">@include('icons.cancel')</a>
                            </p>

                            <div class="relative w-full">
                                <input type="checkbox" id="$post-{{ $post->id }}" class="peer hidden">

                                <div class=" whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none break-words overflow-hidden">
                                    <span class="">{{ $replyTo->content }}</span>
                                </div>

                                @if (strlen($replyTo->content) > 300)
                                <label for="$post-{{ $post->id }}"
                                    class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                                    Read more...
                                </label>

                                <label for="$post-{{ $post->id }}"
                                    class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                                    Show less
                                </label>
                                @endif
                            </div>

                            {{-- <details class="">
                                <summary class="cursor-pointer mt-2 select-none ">Read More...</summary>
                                <p class="whitespace-pre-line">
                                    {{ $replyTo->content }}
                                </p>
                            </details> --}}
                        </div>
                    @endif
                </div>


                <form action="{{ route('posts.store') }}" method="POST" class="formReload h-full" id="postForm">
                    @csrf

                <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                <input type="hidden" name='parent_id' value="{{ $replyTo?->id ?? null  }}">

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
