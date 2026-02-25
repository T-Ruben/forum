<x-layout>
<x-header />

<x-main>

    @foreach ($messages as $message)

        <div class="text-2xl flex-2 min-w-0 wrap-break-word">
            {{ $conversation->title }}
            {{ $messages->links() }}
        </div>
    <section class="my-2 border p-2 flex max-sm:flex-col h-fit bg-gray-200/85 text-black">

{{-- User Profile --}}
    <div class="h-full py-3 bg-gray-400/25 border-gray-400 border shadow-sm shadow-black/50
        text-left flex flex-col items-center justify-center min-w-42 max-w-42
        max-sm:min-w-full max-sm:flex-row max-sm:pl-2 max-sm:max-h-32 max-sm:py-2 max-sm:items-start">
        <div class="w-32 h-32 max-sm:min-w-26  max-sm:min-h-26 max-sm:max-h-26 max-sm:max-w-26 overflow-hidden border shadow-xs shadow-black text-black">
            <a href="{{ $message->user?->user_url }}" class="w-full h-full">
            <img src="{{ $message->user->profile_image_url }}"
                class="w-full h-full object-cover"
                alt="{{ $message->user->display_name ?? 'Deleted Member' }}'s profile image"
                data-pin-nopin="true">
            </a>
        </div>
        <div class="flex flex-col w-full px-2">
            <div>
                <div class="pt-2 font-bold">
                    <a class="hover:underline" href="{{ $message->user?->user_url }}">
                    {{ $message->user->display_name }}
                    </a>
                </div>
                <div class="text-sm text">
                    {{ $message->user->role->label() }}
                </div>
            </div>
            <hr class="border-gray-500 max-sm:hidden my-2">
            <div class="max-sm:hidden">
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Joined: </span></p><p class="text-sm">{{ $message->user->created_at?->format('M d, Y') }}</p>

                </div>
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Messages: </span></p><p class="text-sm">{{ optional($message->user)->messages_count ?? 0 }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Following: </span></p><p class="text-sm">{{ $message->user->following->count() }}</p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm"><span class="text-gray-950/75">Followers: </span></p><p class="text-sm">{{ $message->user->followers->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-2 pr-2 pl-5 w-full min-h-full text-md break-words overflow-hidden ">
        <article class="message-content break-words" id="message-{{ $message->id }}">
            @if ($message->parent)
                <blockquote class="flow-root border border-gray-600 p-1 rounded bg-white/25 ">
                    <div class="border-b b-2 py-2 leading-0">
                        <p class="text-sm inline">Replying to: <span class="font-semibold hover:underline duration-200"><a href="{{ route('conversation.show', [$conversation, 'page' => $message->parent->getPageNumber()]) }}#message-{{ $message->parent_id }}">{{ $message->parent?->user->display_name }}</a></span></p>
                    </div>

                    <div class="relative">
                        <input type="checkbox" id="$message-{{ $message->id }}" class="peer hidden">

                        <div class="whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none">
                            {!! \App\Services\BBCodeParser::parse($message->parent?->content) !!}
                        </div>

                        @if (strlen($message->parent?->content) > 300)
                        <label for="$message-{{ $message->id }}"
                            class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                            Read more...
                        </label>

                        <label for="$message-{{ $message->id }}"
                            class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                            Show less
                        </label>
                        @endif
                    </div>
                </blockquote>
            @endif
            <div class="whitespace-pre-line my-2">{!! \App\Services\BBCodeParser::parse($message->content) !!}</div>
        </article>
        <hr class="border-gray-500 my-2">
        <div class="flex align-bottom">
            <div class="w-full">
            <span class="text-sm text-gray-700/75">
                <x-user-link :user="$message->user" />
            </span>
            <span class="text-sm text-gray-700/75">
                <x-time-display :time="$message->updated_at" :createdAt="$message->created_at" :updatedAt="$message->updated_at"/>
            </span>
            </div>
            <div class="flex gap-5">
                <x-actions.delete-button  :model="$message" />

                @can('update', $message)
                    <a href="{{ route('conversation.show', ['conversation' => $conversation,  'edit_message' => $message, 'page' => request('page')]) }}#messageForm"
                        class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                        Edit
                    </a>
                @endcan


                <a href="{{ route('conversation.show', ['conversation' => $conversation, 'reply_to' => $message->id, 'page' => request('page')]) }}#message-{{ $message->parent_id }}"
                    class="replyReload cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </a>
            </div>
        </div>

    </div>
</section>
    @endforeach

</x-main>

<x-footer />
</x-layout>
