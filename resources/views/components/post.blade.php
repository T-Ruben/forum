@props(['post', 'thread'])

<section class="my-2 border p-2 flex bg-gray-200/75 text-black">

    <div class="h-full py-3 bg-gray-400/25 border-gray-400 border shadow-sm shadow-black/50 float-left text-left flex flex-col items-center justify-center w-42">
        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black">
            <img src="{{ optional($post->author)->getProfileImageUrl() ?? asset('images/default-avatar.png') }}"
                class="w-32 h-32 object-cover"
                alt="{{ $post->author->name ?? 'Deleted user' }}'s profile image">





        </div>
        <div class="flex flex-col w-full px-2">
            <div class="pt-2">
                {{ $post->author->name }}
            </div>
            <div>
                {{ $post->author->role }}
            </div>
            <x-divide />
            <div class="flex justify-between">
                <p class="text-sm"><span class="text-gray-950/75">Joined: </span></p><p>{{ $post->author->created_at?->format('M d, Y') }}</p>

            </div>
            <div class="flex justify-between">
                <p class="text-sm"><span class="text-gray-950/75">Messages: </span></p><p>{{ $post->author->posts_count }}</p>
            </div>
        </div>
    </div>

    <div class="py-2 pr-2 pl-5 w-11/13 h-full text-md wrap-break-word">
        {{ $post->content }}
        <x-divide />
        <div class="h-full w-full">

            {{--
                REPLY HERE?

                 <form action="/posts" method="POST" class="flex justify-end align-text-bottom">
                @csrf
                <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                <input type="hidden" name='parent_id' value="{{ $post->id ?? null  }}">
                <button type="button" name="replyBtn" class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </button>
            </form> --}}

        </div>
    </div>
</section>
