
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
    <div class="flex justify-center bg-slate-950 p-2 mb-2 rounded-b-xl">

        <form action="{{ route('search') }}" method="GET" class="w-full flex gap-2">
            @csrf

            <input type="text"
            name="searchBar"
            id="searchBar"
            class="border-1 w-full bg-white rounded text-black text-lg pl-2"
            placeholder="Search">

            <button type="submit"
                class="text-lg rounded dark:bg-blue-900 border border-fray-400 p-1
                        hover:dark:bg-blue-900/80 duration-200 cursor-pointer">
                        Search
            </button>
        </form>

    </div>
</header>
