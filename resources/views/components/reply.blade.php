<div class="flex shrink-0 gap-3 border-b-1 rounded-b-md bg-gray-300/25 px-1 pt-1"
    id="">
    <div class="w-16 h-16 flex shrink-0 border-1">
        <a href="{{ $reply->user?->user_url }}" class="w-full h-full">
            <img src="{{ asset($reply->user->profile_image_url) }}" class="w-full h-full object-cover"
                alt="{{ $reply->user?->display_name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
        </a>
    </div>
    <div class="overflow-hidden w-full min-w-0 mb-4 mt-2">
        <div>
            <div>
                <a href="{{ $reply->user?->user_url }}"
                    class="hover:text-black/70 duration-200 hover:underline"><strong>{{ $reply->user?->display_name }}</strong></a>
                <div class="post-content whitespace-pre-line break-all md:break-words">{!! $reply->content !!}</div>
            </div>
        </div>
        <div class="flex justify-between text-md">
            <small class="text-gray-300"><x-time-display :time="$reply->created_at" /></small>
            <div class="flex gap-5">
                <x-actions.delete-button :action="route('post.destroy', $post)" :model="$post" />
                <a href="{{ route('users.show', ['user' => $user->id, 'reply_to' => $reply->parent_id, 'page' => request('page')]) }}"
                    class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </a>
            </div>
        </div>
    </div>
</div>


{{-- @if ($reply->recursiveReplies->count() > 0)
    <div class="nested-replies">
        @foreach ($reply->recursiveReplies as $subReply)
            @include('components.reply', ['reply' => $subReply, 'depth' => $depth + 1])
        @endforeach
    </div>
@endif --}}
