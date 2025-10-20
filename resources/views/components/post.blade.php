@props(['post', 'thread'])

<section class="my-2 border p-2 flex bg-gray-200/75 text-black">

    <div class="h-full py-3 bg-gray-400/25 border-gray-400 border shadow-sm shadow-black/50 float-left text-left flex flex-col items-center justify-center w-42">
        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black text-black">
            <a href="{{ route('users.show', $post->author) }}">
            <img src="{{ $post->author->profile_image_url }}"
                class="w-32 h-32 object-cover"
                alt="{{ $post->author->display_name ?? 'Deleted Member' }}'s profile image"
                data-pin-nopin="true">
            </a>
        </div>
        <div class="flex flex-col w-full px-2">
            <div class="pt-2 font-bold">
                <a class="hover:underline" href="{{ route('users.show', $post->author) }}">
                {{ $post->author->display_name }}
                </a>
            </div>
            <div class="text-sm">
                {{ $post->author->role }}
            </div>
            <x-divide />
            <div class="flex justify-between">
                <p class="text-sm"><span class="text-gray-950/75">Joined: </span></p><p class="text-sm">{{ $post->author->created_at?->format('M d, Y') }}</p>

            </div>
            <div class="flex justify-between">
                <p class="text-sm"><span class="text-gray-950/75">Messages: </span></p><p class="text-sm">{{ optional($post->author)->posts_count ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="py-2 pr-2 pl-5 w-11/13 min-h-full text-md wrap-break-word">
        {{ $post->content }}
        <x-divide />
        <div>
        <span class="text-sm text-gray-700/75">
            <x-user-link :user="$post->author" />
        </span>
        <span class="text-sm text-gray-700/75">
            <x-time-display :time="$post->updated_at" />
        </span>
        </div>
        {{-- <div class="">

                REPLY HERE?

                 <form action="/posts" method="POST" class="flex justify-end align-text-bottom">
                @csrf
                <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                <input type="hidden" name='parent_id' value="{{ $post->id ?? null  }}">
                <button type="button" name="replyBtn" class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </button>
            </form>

        </div> --}}
    </div>
</section>
