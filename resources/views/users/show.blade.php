<x-layout>
    <x-header />



    <x-main>

        @push('modals')
            @can('update', $user)
                <x-forms.profile-image :user="$user" />
            @endcan
        @endpush

        <div class="flex gap-2">

        <section class="w-[192px]">
            <div
                class="w-[192px] p-0.5 border"
                @can('update', $user)
                    id="avatarChange"
                @endcan>
                <img
                    src="{{ $user->profile_image_url }}"
                    alt="{{ $user->display_name }}"
                    class="cursor-pointer object-cover w-full"
                    data-pin-nopin="true"
                    itemprop="photo"
                >
            </div>

            @can('follow-user', $user)
                @if (auth()->user()->following->contains($user->id))
                    <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                        @csrf
                            <x-forms.form-button class="w-full mb-5 dark:bg-blue-950/65"> Unfollow </x-forms.form-button>
                    </form>
                    @else
                        <form action="{{ route('users.follow', $user->id) }}" method="POST">
                            @csrf
                                <x-forms.form-button class="w-full mb-5"> Follow </x-forms.form-button>
                        </form>
                @endif
            @endcan

{{-- PRIVATE MESSAGE --}}
            @can('view', $user)
                <div class="flex w-full shrink-0 justify-center">
                    <a href="{{ route('conversation.create', ['user' => $user->id]) }}"
                        class="border rounded p-1 bg-gradient-to-br from-gray-600 to-gray-300 hover:opacity-75 duration-200
                                mt-2">
                        Private Message
                    </a>
                </div>
            @endcan

            <div class="border-0.5 m-2 p-2 ring-1 ring-white">
                <ul>
                    <li class="flex justify-between text-sm"><span class="text-gray-300/75">Joined: </span><span>{{ $user->created_at->format('M d, Y') }}</span></li>
                </ul>
            </div>

            <div class="border-0.5 m-2 p-2 ring-1 ring-white">
                <ul>
                    <li class="flex justify-between text-sm"><span class="text-gray-300/75">Following: </span>
                        <span>{{ $user->following->count() }}</span></li>
                    <li class="flex justify-between text-sm"><span class="text-gray-300/75">Followers: </span>
                        <span>{{ $user->followers->count() }}</li>
                    <li class="flex justify-between text-sm"><span class="text-gray-300/75">Messages: </span>
                        <span>{{ $user->posts->count() }}</li>
                </ul>
            </div>

{{-- FOLLOWING --}}
            <div class="border-0.5 m-2 p-2 ring-1 ring-white">
                <div>
                    <a href="{{ route('members.following', $user) }}" class="hover:underline text-lg">Following:</a>
                </div>
                <hr>
                <div class="w-full flex flex-wrap gap-2">
                    @forelse ($following as $followingUsers)
                    <div class="w-18 h-18 my-1 shrink-0 border-1">
                        <a href="{{ route('users.show', $followingUsers) }}" class="block">
                            <img src="{{ asset($followingUsers->profile_image_url) }}"
                                alt="{{ $followingUsers->display_name }}"
                                title="{{ $followingUsers->display_name }}"
                                class="w-18 h-18 object-cover ring-indigo-700 ring"
                                data-pin-nopin="true">
                        </a>
                    </div>
                    @empty
                    <p class="text-sm">Not following anyone currently.</p>
                    @endforelse
                </div>
            </div>
{{-- FOLLOWERS --}}
            <div class="border-0.5 m-2 p-2 ring-1 ring-white">
                <div>
                    <a href="{{ route('members.followers', $user) }}" class="hover:underline text-lg">Followers:</a>
                </div>
                <hr>
                <div class="w-full flex flex-wrap gap-2">
                    @forelse ($followers as $followerUsers)
                    <div class="w-18 h-18 my-1 shrink-0 border-1">
                        <a href="{{ route('users.show', $followerUsers) }}" class="block">
                            <img src="{{ asset($followerUsers->profile_image_url) }}"
                                alt="{{ $followerUsers->display_name }}"
                                title="{{ $followerUsers->display_name }}"
                                class="w-18 h-18 object-cover ring-indigo-700 ring"
                                data-pin-nopin="true">
                        </a>
                    </div>
                    @empty
                    <p class="text-sm">Not followed by anyone currently.</p>
                    @endforelse
                </div>
            </div>



        </section>

        <section class="w-full min-h-full break-words overflow-hidden">
            <div class="mb-10">
                <p class="text-xl">{{ $user->display_name }}</p>
                <p class="text-sm"><span>{{ $user->profile_summary }}</span></p>
                <hr>
            </div>

                <div class="post-content break-words">
                    @if ($replyTo)
                        <div class="mb-4 p-3 border rounded text-sm border-gray-600 text-black">
                            <p class="flex justify-between border-b">
                                <span>Replying to <a href="#post-{{ $replyTo->id }}"
                                    class="hover:underline font-semibold duration-200">{{ $replyTo->user->display_name }}</a></span>
                                <a href="{{ route('users.show', ['user' => $user->id,  'page' => request('page')]) }}"
                                    class="formReload hover:text-red-500/75 duration-200">@include('icons.cancel')</a>
                            </p>

                            <div class="relative w-full">
                                <input type="checkbox" id="load-more-{{ $replyTo->id }}" class="peer hidden">

                                <div class=" whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none break-words overflow-hidden">
                                    <span class="">{{ $replyTo->content }}</span>
                                </div>

                                @if (strlen($replyTo->content) > 300)
                                <label for="load-more-{{ $replyTo->id }}"
                                    class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                                    Read more...
                                </label>

                                <label for="load-more-{{ $replyTo->id }}"
                                    class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                                    Show less
                                </label>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

            <div class="mb-3 flex gap-3">
                <div class="w-20 h-20 flex shrink-0 border-1">
                    @auth
                    <a href="{{ route('users.show', auth()->user()) }}" class="w-full h-full">
                        <img src="{{ asset(auth()->user()?->profile_image_url) }}" class="w-full h-full object-cover"
                            alt="{{ auth()->user()->name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                    </a>
                    @endauth
                    @guest
                        <a href="{{ route('login')}}">
                        <img src="{{ asset('images/default-avatar.png') }}" class="w-20 h-20 object-cover"
                            alt="Guest's profile image" data-pin-nopin="true">
                        </a>
                    @endguest
                </div>

                @php
                    $isEdit = isset($editPost);
                    $action = $isEdit ? route('profile.post.update', $editPost) : route('user.posts.store', $user);
                @endphp

                <form action="{{ $action }}" method="POST" class="formReload w-full" id="postForm">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $replyTo?->id ?? null }}">
                    <input type="hidden" name="profile_user_id" value="{{ $user->id }}">

                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        class="w-full p-2 bg-gray-200 text-black resize-none overflow-hidden border border-gray-600
                        outline-none"
                        placeholder="Write your post...">{{ old('content', $isEdit ? $editPost->content : '') }}</textarea>
                    <div>

                    </div>
                    <div class="my-auto flex-2 justify-center">
                        @error('content')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($isEdit)
                        <a href="{{ route('users.show', [$user, 'page' => request('page')]) }}"
                        class="text-gray-500 mr-4">
                            Cancel Edit
                        </a>
                    @endif

                    <button type="submit"
                        class="text-white dark:bg-blue-950 hover:dark:bg-blue-900/80 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
                        Post Reply
                    </button>

                </form>
            </div>

            <div class="bg-gray-300/60 text-black p-2 w-full max-w-full overflow-x-hidden">
                @forelse ($posts as $post)
                @if (!$post->parent)
                    <div class="flex shrink-0 gap-3">
                        <div class="w-20 h-20 flex shrink-0 border-1">
                            <a href="{{ $post->user?->user_url }}" class="w-full h-full">
                                <img src="{{ asset($post->user->profile_image_url) }}" class="w-full h-full object-cover"
                                    alt="{{ $post->user?->display_name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                            </a>
                        </div>
                        <div class="overflow-hidden w-full min-w-0 mb-4 mt-2">
                            <div id="post-{{ $post->id }}">
                                <div>
                                    <a href="{{ $post->user?->user_url }}"
                                        class="hover:text-black/70 duration-200 hover:underline"><strong>{{ $post->user->display_name }}</strong></a>
                                    <div class="post-content whitespace-pre-line break-all md:break-words pb-10">{!! $post->content !!}</div>
                                </div>
                                <div class="flex justify-between">
                                    <small class="text-gray-300"><x-time-display :time="$post->updated_at" :createdAt="$post->created_at" :updatedAt="$post->updated_at" /></small>

                                    <div class="flex gap-5">
                                        <x-actions.delete-button :action="route('profile.post.destroy', $post)" :model="$post" />

                                        @can('update', $post)
                                            <a href="{{ route('users.show', ['user' => $user->id, 'edit_post' => $post->id, 'page' => request('page')]) }}"
                                                class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                                                Edit
                                            </a>
                                        @endcan

                                        <a href="{{ route('users.show', ['user' => $user->id, 'reply_to' => $post->id, 'page' => request('page')]) }}"
                                            class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                                            Reply
                                        </a>
                                    </div>

                                </div>
                            </div>

                            <div>
                                <input type="checkbox" id="load-replies-{{ $post->id }}" class="peer hidden">

                                @foreach ($post->replies as $reply)
                                <div class="{{ $loop->index >= 3 ? 'hidden peer-checked:block' : '' }}">
                                    @include('components.reply', ['reply' => $reply])
                                </div>
                                @endforeach

                                @if ($post->replies->count() > 3)
                                    <label for="load-replies-{{ $post->id }}" class="select-none cursor-pointer text-blue-500 hover:underline block peer-checked:hidden">
                                        Show more...
                                    </label>

                                    <label for="load-replies-{{ $post->id }}" class="select-none cursor-pointer text-blue-500 hover:underline hidden peer-checked:block">
                                        Show less...
                                    </label>
                                @endif
                            </div>



                        </div>
                    </div>
                    <div>

                    </div>

                    <hr class="mb-3">
                @endif
                @empty
                    <p>No posts on this profile yet.</p>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
            </div>


        </section>

        </div>

    </x-main>

    <x-footer />
</x-layout>
