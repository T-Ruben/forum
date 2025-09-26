
<header>
    <div class="dark:bg-blue-950 flex justify-between py-5 text-center items-center rounded-t-xl">
        <div class="flex">
            <a href="/" class="mx-10 font-medium text-3xl"><h1>Game Updates</h1></a>
            <nav class="flex gap-2 justify-start">
                <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                <x-nav-link href="#" :active="request()->is('#')">Blogs</x-nav-link>
                <x-nav-link href="#" :active="request()->is('#')">Members</x-nav-link>
            </nav>
        </div>

        <div class="flex mx-10">
            <nav class="">
            @guest
                <x-nav-link href="/register" :active="request()->is('#')">Register</x-nav-link>
                <x-nav-link href="/auth/login" :active="request()->is('#')">Login</x-nav-link>
            @endguest

            @auth
                <div class="">
                    <h3>{{ auth()->user()->name }}!</h3>
                    <a href="#">View Profile</a>
                </div>
            @endauth
        </nav>
        </div>


    </div>
    {{-- SEARCH BAR HERE --}}
    <div class="flex bg-slate-950 p-2 mb-2 rounded-b-xl">
        test
    </div>
</header>
