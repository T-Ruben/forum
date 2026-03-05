<div x-data="{ open: false }" @click.outside="open = false" class="relative z-10">
    <div class="relative field-sizing-content">
        <div @click="open = !open" class="w-full select-none">
                <h3 class="flex items-center text-center cursor-pointer text-lg hover:dark:bg-blue-900/70 w-full h-10 m-1 rounded-md">
                @include('icons.arrow-down')
                <span>{{ auth()->user()->name }}</span><img src="{{ Auth::user()->profile_image_url }}" alt=""
                    class="size-8 ml-2 rounded-full outline -outline-offset-1 outline-white/10"
                    data-pin-nopin="true"/></h3>
        </div>
    </div>

        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="p-2 absolute w-80 right-0 top-full bg-gray-600/95 border transform
                -translate-y-2 transition-all border-gray-400/50 duration-300">
            <div class="flex">
                <a href="{{ route('users.show', auth()->user()->id) }}">
                <img src="{{ asset(auth()->user()->profile_image_url) }}" class="w-32 h-32 object-cover"
                    alt="{{ auth()->user()->name }}'s profile image" data-pin-nopin="true">
                </a>


                <ul class="p-2 flex-2 w-full">
                    <li class="hover:opacity-75"><a class="hover:underline" href="{{ route('users.show', auth()->user()->id) }}">{{ auth()->user()->name }}</a></li>
                <li class="hover:opacity-75"><a class="hover:underline" href="{{ route('users.show', auth()->user()->id) }}">View Your Profile</a></li>
                    <li class="hover:opacity-75"><a class="hover:underline flex" href="{{ route('notifications.index', auth()->user()) }}">Notifications</a></li>
                </ul>

            </div>
            <hr class="border-gray-500 my-2">
            <div class="grid grid-cols-2 gap-2">
                <div class="">
                    <ul class="">
                        <li>
                            <x-link page="settings_link" :active="request()->routeIs('settings.personal')" href="{{ route('settings.personal') }}">Personal
                                Details</x-link>
                        </li>
                        <li>
                            <x-link page="settings_link" :active="request()->routeIs('settings.privacy')"
                                href="{{ route('settings.privacy') }}">Privacy</x-link>
                        </li>
                    </ul>
                </div>
                <div class="">
                    <ul class="">
                        <li>
                            <x-link page="settings_link" :active="request()->routeIs('settings.threads')"
                                href="{{ route('settings.threads') }}">Created Threads</x-link>
                        </li>
                        <li>
                            <x-link page="settings_link" :active="request()->routeIs('settings.conversations')"
                                href="{{ route('settings.conversations') }}">Conversations</x-link>
                        </li>
                    </ul>
                </div>

            </div>
            <hr class="border-gray-500 my-2">
            <div>
                <div class="hover:bg-gray-400/50 w-fit p-1 duration-200">
                    <form class="" action="{{ route('logout.destroy') }}" method="POST">
                        @csrf
                        <button type="submit" class="cursor-pointer">Logout</button>
                    </form>
                </div>
            </div>
        </div>
</div>
