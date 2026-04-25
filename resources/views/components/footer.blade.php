<footer class="bg-slate-950 mb-16 sm:pb-2 w-full relative bottom-0 rounded-b-md mt-2">
    <h3>Copyrights / Contacts</h3>
</footer>

<div class="fixed sm:hidden bottom-0 w-screen h-14 bg-linear-to-r from-gray-900 to-slate-950">
    <div class="flex shrink-0 justify-center">
        <nav class="flex gap-2 pt-1 pl-1 justify-start items-center">
            <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">Home</x-nav-link>
            {{-- <x-nav-link href="#" :active="request()->routeIs('')">Blogs</x-nav-link> --}}
            <x-nav-link href="{{ route('members.index') }}" :active="request()->routeIs('members.index')">Members</x-nav-link>
        </nav>
    </div>
</div>

