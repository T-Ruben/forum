
<header >
    <div class="dark:bg-blue-950 flex py-5 text-center items-center rounded-t-xl">
        <a href="/" class="mx-10 font-medium text-3xl"><h1>Game Updates</h1></a>
        <nav class="flex gap-2">
            <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
            <x-nav-link href="#" :active="request()->is('#')">Blogs</x-nav-link>
            <x-nav-link href="#" :active="request()->is('#')">Members</x-nav-link>
        </nav>
    </div>
    {{-- SEARCH BAR HERE --}}
    <div class="flex bg-slate-950 p-2 mb-2 rounded-b-xl">
        test
    </div>
</header>
