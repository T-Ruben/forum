
<header>
    <div class="dark:bg-blue-950 flex justify-between py-5 items-center rounded-t-xl">
        <div class="flex">
            <a href="/" class="mx-10 font-medium text-3xl"><h1>Game Updates</h1></a>
            <nav class="flex gap-2 justify-start items-center">
                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">Home</x-nav-link>
                <x-nav-link href="#" :active="request()->routeIs('')">Blogs</x-nav-link>
                <x-nav-link href="{{ route('members.index') }}" :active="request()->routeIs('members.index')">Members</x-nav-link>
            </nav>
        </div>

        <div class="flex mx-10">
            <nav class="">
            @guest
                <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">Register</x-nav-link>
                <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">Login</x-nav-link>
            @endguest

            @auth

                <x-user-menu />

            @endauth

            </nav>
        </div>


    </div>
    {{-- SEARCH BAR HERE --}}
    <div class="flex-2 justify-center bg-slate-950 p-2 rounded-b-xl">

        <form action="{{ route('search') }}" method="GET" class="w-full flex-2 gap-2">

            <input type="text"
            name="query"
            id="query"
            class="border-1 bg-white rounded text-black text-lg pl-2 peer w-1/3"
            autocomplete="off"
            placeholder="Search">

            <button type="submit"
                class="text-lg rounded dark:bg-blue-900 border border-fray-400 px-1
                        hover:dark:bg-blue-900/80 duration-200 cursor-pointer">
                        Search
            </button>
            <div class="w-full opacity-0 peer-focus:opacity-100 hover:opacity-100 select-none">
                <div class="bg-gray-100 text-black p-2 w-fit absolute border inset-shadow-2xs inset-shadow-black">
                    <input type="checkbox" name="threadOnly" id="threadOnly">
                    <label for="threadOnly" class="">Search thread titles only</label>
                </div>
            </div>
        </form>




    </div>
    {{-- <div>
        <div class="bg-gray-100 text-black p-2 w-fit">
            <input type="checkbox" name="searchCheck" id="searchCheck">
            <label for="searchCheck" class="">Search thread titles only</label>
        </div>
    </div> --}}
</header>
