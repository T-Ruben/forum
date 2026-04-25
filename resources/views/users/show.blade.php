<x-layout>
    <x-header />



    <x-main>

        @push('modals')
            @can('update', $user)
                <x-forms.profile-image :user="$user" />
            @endcan
        @endpush

        <div class="flex-2 sm:flex mt-2 sm:mt-0 sm:gap-2">

            <section class="w-full sm:w-[192px]">
                <div
                    class="w-full sm:w-[192px] p-0.5 border"
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

                <div class="flex flex-col items-center justify-center sm:hidden">
                    <p class="text-2xl font-bold">{{ $user->display_name }}</p>
                    <p class="text-sm"><span>{{ $user->profile_summary }}</span></p>
                </div>

                @can('follow-user', $user)
                    @if (auth()->user()->following->contains($user->id))
                        <form action="{{ route('users.unfollow', $user->id) }}" method="POST">
                            @csrf
                                <x-forms.form-button class="w-full mb-1 bg-blue-950/65"> Unfollow </x-forms.form-button>
                        </form>
                        @else
                            <form action="{{ route('users.follow', $user->id) }}" method="POST">
                                @csrf
                                    <x-forms.form-button class="w-full mb-1"> Follow </x-forms.form-button>
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
                            <span>{{ $user->following_count }}</span></li>
                        <li class="flex justify-between text-sm"><span class="text-gray-300/75">Followers: </span>
                            <span>{{ $user->followers_count }}</li>
                        <li class="flex justify-between text-sm"><span class="text-gray-300/75">Messages: </span>
                            <span>{{ $user->countMessagePostTotal() }}</li>
                    </ul>
                </div>

    {{-- FOLLOWING --}}
                <div class="border-0.5 m-2 p-2 ring-1 ring-white">
                    <div>
                        <a href="{{ route('members.following', $user) }}" class="hover:underline active:underline
                            text-lg font-semibold">Following:</a>
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
                        <a href="{{ route('members.followers', $user) }}" class="hover:underline active:underline
                            text-lg font-semibold">Followers:</a>
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

            {{-- SECOND SECTION HERE --}}

            <section class="w-full min-h-fit break-words overflow-hidden">

                <livewire:livewire.profile.show :user="$user" />

            </section>

        </div>

    </x-main>

    <x-footer />
</x-layout>
