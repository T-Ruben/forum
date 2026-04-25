<x-layout>

    <x-header />
    <x-main>
        <div class="grid grid-cols-[40%_60%] sm:grid-cols-[25%_75%] sm:gap-4 ml-2 sm:ml-0 mt-2 sm:mt-0">
            <aside>
                <h2 class="text-2xl font-bold mb-2">{{ $title }}</h2>

                <div>
                    <h3 class="p-2 relative bg-blue-950">Settings</h3>
                </div>
                <ul class="">
                    <li>
                        <x-link page="settings_link" :active="request()->routeIs('settings.personal')" href="{{ route('settings.personal') }}">Personal
                            Details</x-link>
                        <div class="w-full h-px bg-gray-600"></div>
                    </li>
                    <li>
                        <x-link page="settings_link" :active="request()->routeIs('settings.privacy')"
                            href="{{ route('settings.privacy') }}">Privacy</x-link>
                        <div class="w-full h-px bg-gray-600"></div>
                    </li>
                    <li>
                        <x-link page="settings_link" :active="request()->routeIs('settings.threads')"
                            href="{{ route('settings.threads') }}">Created Threads</x-link>
                        <div class="w-full h-px bg-gray-600"></div>
                    </li>
                    <li>
                        <x-link page="settings_link" :active="request()->routeIs('settings.conversations')"
                            href="{{ route('settings.conversations') }}">Conversations</x-link>
                        <div class="w-full h-px bg-gray-600"></div>
                    </li>
                    <li>
                        <form action="{{ route('logout.destroy') }}" method="POST">
                            @csrf
                            <button
                                class="w-fit text-left cursor-pointer text-red-400 hover:text-red-600">Logout</button>
                        </form>
                    </li>
                </ul>

            </aside>

            <section class="pl-2 sm:pl-0">
                {{ $slot }}
            </section>
        </div>
    </x-main>
    <x-footer />

</x-layout>
