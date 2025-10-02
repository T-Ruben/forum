<x-layout>

    <x-header />

    <x-main>
        <div class="text-xl flex-2">
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

        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black m-2">
            @foreach ($posts as $post)
                <img src="{{ optional($post->author)->getProfileImageUrl() ?? asset('images/default-avatar.png') }}"
                class="w-32 h-32 object-cover"
                alt="{{ $post->author->name ?? 'Deleted user' }}'s profile image">
            @endforeach



        </div>

        <div class="flex-2 flex-col h-2/3 m-2">

                <form action="/posts" method="POST" class="h-full">
                    @csrf

                    <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                    <input type="hidden" name="parent_id" value="{{ $post->id ?? null }}">


                    <textarea class="h-full w-full resize-none outline-0 border-1 p-1 text-black bg-gray-100" name="content" id="content" placeholder="Write your reply..."></textarea>
                    @auth
                        <button type="submit" class="text-white dark:bg-blue-900 hover:dark:bg-blue-900/75 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
                        Post Reply
                        </button>
                    @endauth
                    @guest
                    <div class="flex">
                        <a href="{{ url('login') }}" class="text-white dark:bg-blue-900 hover:dark:bg-blue-900/75
                        cursor-pointer duration-200 ml-auto block text-center no-underline border rounded-md p-1">
                            Post Reply (Login required)
                        </a>
                    </div>
                    @endguest

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

