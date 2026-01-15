<x-layout>
    <x-header />



    <x-main>

        @push('modals')
            @can('edit-user', $user)
                <x-forms.profile-image :user="$user" />
            @endcan
        @endpush

        <div class="flex gap-2">

        <section class="w-[192px]">
            <div
                class="w-[192px] p-0.5 border"
                @can('edit-user', $user)
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
                @if (auth()->user()->following()->where('id', $user->id)->exists())
                    <form action="{{ route('users.follow', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                            <x-forms.form-button class="w-full mb-5 dark:bg-blue-950/65"> Unfollow </x-forms.form-button>
                    </form>
                    @else
                        <form action="{{ route('users.follow', $user->id) }}" method="POST">
                            @csrf
                                <x-forms.form-button class="w-full mb-5"> Follow </x-forms.form-button>
                        </form>
                @endif
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
        </section>


            <section class="w-full">
                <div class="mb-10">
                    <p class="text-xl">{{ $user->display_name }}</p>
                    <p class="text-sm"><span>{{ $user->profile_summary }}</span></p>
                    <hr>
                </div>

                <div class="mb-3 flex gap-3">
                            <div class="w-20 h-20 flex shrink-0 border-1">
                                @auth
                                <a href="{{ route('users.show', auth()->user()) }}">
                                    <img src="{{ asset(auth()->user()?->profile_image_url) }}" class="w-full h-full object-cover"
                                        alt="{{ auth()->user()->name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                                </a>
                                @endauth
                                @guest
                                    <a href="{{ route('login')}}">
                                    <img src="{{ asset('images/default-avatar.png') }}" class="w-full h-full object-cover"
                                        alt="Guest's profile image" data-pin-nopin="true">
                                    </a>
                                @endguest
                            </div>
                    <form action="{{ route('user.posts.store', $user) }}" method="POST" class="formReload w-full" id="postForm">
                        @csrf

                        <textarea
                            id="content"
                            name="content"
                            rows="6"
                            class="w-full p-2 bg-gray-200 text-black resize-none overflow-hidden border border-gray-600
                            outline-none"
                            placeholder="Write your post...">{{ old('content') }}</textarea>

                        <div class="my-auto block absolute">
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

                <div class="bg-gray-300/60 text-black p-2 w-full max-w-full overflow-x-hidden">
                    @forelse ($posts as $post)
                        <div class="flex shrink-0 gap-3">
                            <div class="w-20 h-20 flex shrink-0 border-1">
                                <a href="{{ route('users.show', $post->user) }}">
                                    <img src="{{ asset($post->user->profile_image_url) }}" class="w-full h-full object-cover"
                                        alt="{{ $post->user->name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                                </a>
                            </div>
                            <div class="overflow-hidden w-full min-w-0 mb-4 mt-2">
                                <a href="{{ route('users.show', $post->user) }}"
                                    class="hover:text-black/50 duration-200"><strong>{{ $post->user->name }}</strong></a>
                                <div class="post-content whitespace-pre-line break-all md:break-words">
                                    {!! $post->content !!}
                                </div>
                                <small class="text-gray-500"><x-time-display :time="$post->created_at" /></small>
                            </div>
                        </div>
                        <hr class="mb-3">
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
