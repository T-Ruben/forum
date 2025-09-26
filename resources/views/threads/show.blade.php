<x-layout>

    <x-header />

    <x-main>
        <div class="text-xl">
            {{ $thread->title }}
        </div>
        <x-divide />
        <div>
            @foreach ($thread->posts as $post)
                <x-post :post="$post" :thread="$thread" />
            @endforeach
        </div>

        <div class="flex border border-black min-h-[200px] h-auto w-full max-w-full bg-gray-200/75">

        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black m-2">
            @foreach ($thread->posts as $posts)
                <img src="{{ optional($post->user)->getProfileImageUrl() ?? asset('images/default-avatar.png') }}"
                class="w-32 h-32 object-cover"
                alt="{{ $post->user->name ?? 'Deleted user' }}'s profile image">
            @endforeach



        </div>

        <div class="flex-2 flex-col h-2/3 m-2">

                <form action="/posts" method="POST" class="h-full">
                    @csrf

                    <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                    <input type="hidden" name="parent_id" value="{{ $post->id ?? null }}">


                    <textarea class="h-full w-full resize-none outline-0 border-1 p-1 text-black bg-gray-100" name="content" id="content" placeholder="Write your reply..."></textarea>

                    <button type="submit" class="text-white dark:bg-blue-900 hover:dark:bg-blue-900/75 cursor-pointer duration-200 ml-auto block border rounded-md p-1">

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
    </x-main>

    <x-footer />
</x-layout>

