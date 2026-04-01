
<header>
    <div class="dark:bg-blue-950 flex py-5 items-center rounded-t-xl">
        <div class="flex shrink-0">
            <a href="/" class="mx-10 font-medium text-3xl shrink-0"><h1>Game Updates</h1></a>
            <nav class="flex gap-2 justify-start items-center">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">Home</x-nav-link>
                <x-nav-link href="#" :active="request()->routeIs('')">Blogs</x-nav-link>
                <x-nav-link href="{{ route('members.index') }}" :active="request()->routeIs('members.index')">Members</x-nav-link>
            </nav>
        </div>

        <div class="flex mr-10 justify-end w-full">
            <nav class="">
                @guest
                    <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">Register</x-nav-link>
                    <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">Login</x-nav-link>
                @endguest
            </nav>

            @auth
                <div class="my-auto w-fit relative inline-block cursor-pointer group"
                    x-data="{ open: false }"
                    @click="open = !open"
                    @click.outside="open = false">
                    @if (Auth::user()->total >= 1)
                        <button class="absolute -translate-y-2 -translate-x-1 z-50 w-6 h-6 rounded-lg text-xs
                            font-bold text-white border border-black bg-red-600 text-shadow-lg/25 cursor-pointer">
                            {{ Auth::user()->total }}
                        </button>
                    @endif
                    <button
                        type="button"
                        class="relative ml-auto shrink-0 rounded-full p-1 text-gray-400 group-hover:text-white
                            group-focus:outline-2 group-focus:outline-offset-2 group-focus:outline-indigo-500 cursor-pointer">
                        <span class="absolute -inset-1.5"></span>
                        <span class="sr-only">View notifications</span>
                        @include('icons.bell')
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute right-0 w-80 shadow-lg">
                        @include('notifications.dropdown')
                    </div>
                </div>

                <x-user-menu />

            @endauth


        </div>


    </div>
    {{-- SEARCH BAR HERE --}}
    <div class="flex-2 justify-center bg-slate-950 p-2 rounded-b-xl" x-data="{ open: false }">

        <form action="{{ route('search') }}" method="GET" class="w-full flex-2 gap-2" @click.outside="open = false">

            <input
            @focus="open = true"
            @input="open = true"
            type="text"
            name="query"
            id="query"
            class="border-1 bg-white rounded text-black text-lg pl-2 w-1/3"
            autocomplete="off"
            placeholder="Search">

            <button type="submit"
                class="text-lg rounded dark:bg-blue-900 border border-fray-400 px-1
                        hover:dark:bg-blue-900/80 duration-200 cursor-pointer">
                        Search
            </button>
            <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="w-full select-none">
                <div class="bg-gray-100 text-black p-2 w-fit absolute border inset-shadow-2xs inset-shadow-black">
                    <input type="checkbox" name="threadOnly" id="threadOnly">
                    <label for="threadOnly" class="">Search thread titles only</label>
                </div>
            </div>
        </form>

    </div>

</header>

