@props(['post', 'thread'])

<section class="my-2 border p-2 flex max-sm:flex-col h-fit bg-gray-200/85 text-black">

{{-- User Profile --}}
    <div class="h-full py-3 bg-gray-400/25 border-gray-400 border shadow-sm shadow-black/50
        text-left flex flex-col items-center justify-center min-w-42 max-w-42
        max-sm:min-w-full max-sm:flex-row max-sm:pl-2 max-sm:max-h-32 max-sm:py-2 max-sm:items-start">
        <div class="w-32 h-32 max-sm:min-w-26  max-sm:min-h-26 max-sm:max-h-26 max-sm:max-w-26 overflow-hidden border shadow-xs shadow-black text-black">
            <a href="{{ $post->user?->user_url }}" class="w-full h-full">
            <img src="{{ $post->user->profile_image_url }}"
                class="w-full h-full object-cover"
                alt="{{ $post->user->display_name ?? 'Deleted Member' }}'s profile image"
                data-pin-nopin="true">
            </a>
        </div>
        <div class="flex flex-col w-full px-2">
            <div>
                <div class="pt-2 font-bold">
                    <a class="hover:underline" href="{{ $post->user?->user_url }}">
                    {{ $post->user->display_name }}
                    </a>
                </div>
                <div class="text-sm text">
                    {{ $post->user->role->label() }}
                </div>
            </div>
            <hr class="border-gray-500 max-sm:hidden my-2">
            <div class="max-sm:hidden">
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Joined: </span></p><p class="text-sm">{{ $post->user->created_at?->format('M d, Y') }}</p>

                </div>
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Messages: </span></p><p class="text-sm">{{ optional($post->user)->countMessagePostTotal() ?? 0 }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Following: </span></p><p class="text-sm">{{ $post->user->following_count }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Followers: </span></p><p class="text-sm">{{ $post->user->followers_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2 pr-2 pl-5 w-full min-h-full text-md break-words overflow-hidden ">
        <article class="post-content break-words" id="post-{{ $post->id }}">
            @if ($post->parent)
                <blockquote class="flow-root border border-gray-600 p-1 rounded bg-white/25 ">
                    <div class="border-b b-2 py-2 leading-0">
                        <p class="text-sm inline">Replying to: <span class="font-semibold hover:underline duration-200"><a href="{{ route('threads.show', [$thread, $thread->slug, 'page' => $post->parent->getPageNumber()]) }}#post-{{ $post->parent_id }}">{{ $post->parent?->user->display_name }}</a></span></p>
                    </div>

                    <div class="relative">
                        <input type="checkbox" id="$post-{{ $post->id }}" class="peer hidden">

                        <div class="whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none">
                            {!! \App\Services\BBCodeParser::parse($post->parent?->content) !!}
                        </div>

                        @if (strlen($post->parent?->content) > 300)
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
                </blockquote>
            @endif
            <div class="whitespace-pre-line my-2">{!! \App\Services\BBCodeParser::parse($post->content) !!}</div>
        </article>
        <hr class="border-gray-500 my-2">
        <div class="flex align-bottom">
            <div class="w-full">
            <span class="text-sm text-gray-700/75">
                <x-user-link :user="$post->user" />
            </span>
            <span class="text-sm text-gray-700/75">
                <x-time-display :time="$post->updated_at" :createdAt="$post->created_at" :updatedAt="$post->updated_at"/>
            </span>
            </div>
            <div class="flex gap-5">
                <x-actions.delete-button :action="route('threads.post.destroy', $post)" :model="$post" />

                @can('update', $post)
                    <a href="{{ route('threads.show', ['thread' => $thread->id, $thread->slug, 'edit_post' => $post, 'page' => request('page')]) }}#postForm"
                        class="cursor-pointer text-blue-900 hover:text-blue-900/75 hover:underline duration-200 font-semibold">
                        Edit
                    </a>
                @endcan


                <a href="{{ route('threads.show', ['thread' => $thread->id, 'reply_to' => $post->id, $thread->slug, 'page' => request('page')]) }}#post-{{ $post->parent_id }}"
                    class="replyReload cursor-pointer text-blue-900 hover:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </a>
            </div>
        </div>

    </div>
</section>
