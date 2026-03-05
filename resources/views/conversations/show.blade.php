<x-layout>
<x-header />

<x-main>

    <div class="text-2xl flex-2 min-w-0 wrap-break-word mb-2">
        {{ $conversation->title }}
        {{ $messages->links() }}
    </div>

    <livewire:livewire.user-search :conversation="$conversation"/>

    <aside class="border w-full bg-linear-to-br from-gray-500 to-gray-300 text-black">
        <div class="w-full mb-2 bg-linear-to-tr from-gray-500 to-gray-200">
            <span class="pl-2 text-lg">Members:</span>
            <hr/>
        </div>
        <ul class="p-1 flex flex-wrap">
            @foreach ($conversation->users as $user)
                <li class="border m-0.5 p-1 flex gap-1 w-75">
                    <div class="w-24 h-24 max-sm:min-w-26  max-sm:min-h-26 max-sm:max-h-26 max-sm:max-w-26 overflow-hidden border shadow-xs shadow-black text-black">
                        <a href="{{ $user->user_url }}" class="w-full h-full">
                        <img src="{{ $user->profile_image_url }}"
                            class="w-full h-full object-cover"
                            alt="{{ $user->display_name ?? 'Deleted Member' }}'s profile image"
                            data-pin-nopin="true">
                        </a>
                    </div>
                    <a href="{{ $user->user_url }}"
                        class="inline w-fit h-fit hover:underline">
                        {{ $user->display_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    @foreach ($messages as $message)

{{-- User Profile --}}
    <div>
        <section class="my-2 border p-2 flex max-sm:flex-col h-fit bg-gray-200/85 text-black">
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
{{-- Content --}}
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
                        <x-actions.delete-button :action="route('message.destroy', $message)" :model="$message" />

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
    </div>

{{-- Message form/area --}}

    <div class="flex border border-black min-h-[200px] h-auto w-full max-w-full bg-gray-200/85">

        <div class="w-32 h-32 overflow-hidden border shadow-xs shadow-black m-2 text-black max-sm:hidden">
            @foreach ($messages as $message)
                @auth
                    <a href="{{ route('users.show', auth()->user()->id) }}" class="w-full h-full">
                        <img src="{{ asset(auth()->user()->profile_image_url) }}" class="w-full h-full object-cover"
                            alt="{{ auth()->user()->display_name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                    </a>
                @endauth

                @guest
                    <a href="#">
                        <img src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover"
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
                            <span>Replying to <a href="#message-{{ $replyTo->id }}"
                                class="hover:underline font-semibold duration-200">{{ $replyTo->user->display_name }}</a></span>
                            <a href="{{ route('conversation.show', [$conversation, 'page' => request('page')]) }}"
                                class="formReload hover:text-red-500/75 duration-200">@include('icons.cancel')</a>
                        </p>

                        <div class="relative w-full">
                            <input type="checkbox" id="$message-{{ $message->id }}+{{ $message->user_id }}" class="peer hidden">

                            <div class=" whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none break-words overflow-hidden">
                                <span class="">{{ $replyTo->content }}</span>
                            </div>

                            @if (strlen($replyTo->content) > 300)
                            <label for="$message-{{ $message->id }}+{{ $message->user_id }}"
                                class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                                Read more...
                            </label>

                            <label for="$message-{{ $message->id }}+{{ $message->user_id }}"
                                class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                                Show less
                            </label>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            @php
                $isEdit = isset($editMessage);
                $action = $isEdit ? route('conversation.message.update', $editMessage) : route('message.store');
            @endphp

            <form action="{{ $action }}" method="POST" class="formReload h-full" id="messageForm">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
            <input type="hidden" name='parent_id' value="{{ $replyTo?->id ?? null  }}">

                <x-editor />

                <textarea
                    id="content"
                    name="content"
                    rows="6"
                    class="w-full p-2 bg-gray-200 text-black resize-none overflow-hidden border border-gray-600
                    outline-none"
                    placeholder="Write your post...">{{ old('content', $isEdit ? $editMessage->content : '') }}</textarea>

                <div class="my-auto block">
                    @error('content')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                @if($isEdit)
                    <a href="{{ route('conversation.show', [$conversation, 'page' => request('page')]) }}"
                    class="text-red-500 mr-4 border-2 rounded p-0.5 hover:text-red-700/70">
                        Cancel Edit
                    </a>
                @endif
                <button type="submit"
                    class="text-white dark:bg-blue-950 hover:dark:bg-blue-900/80 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
                    {{ $isEdit ? 'Save Changes' : 'Post Reply' }}
                </button>

            </form>
        </div>
    </div>

    <div class="text-2xl flex-2 min-w-0 wrap-break-word mt-4">
        {{ $messages->links() }}
    </div>

</x-main>

<x-footer />
</x-layout>
