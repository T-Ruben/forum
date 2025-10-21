<div class="">
    <div class="relative group">
        <a href="{{ route('users.show', auth()->user()->id) }}">
            <h3 class=" text-center cursor-pointer text-lg hover:dark:bg-blue-900/70 w-25 h-10 pt-2 m-1 rounded-md">
            {{ auth()->user()->name }}</h3>
        </a>

        <div class="p-2 absolute w-80 right-0 top-full pointer-events-none opacity-0 bg-gray-500/75 border transform
                -translate-y-2 transition-all border-gray-400/50 duration-300 group-hover:opacity-100
                group-hover:pointer-events-auto group-hover:translate-y-0 z-50">
            <div class="flex">
                <a href="{{ route('users.show', auth()->user()->id) }}">
                <img src="{{ asset(auth()->user()->profile_image_url) }}" class="w-32 h-32 object-cover"
                    alt="{{ auth()->user()->name }}'s profile image" data-pin-nopin="true">
                </a>


                <ul class="p-2">
                    <li><a class="hover:underline" href="{{ route('users.show', auth()->user()->id) }}">{{ auth()->user()->name }}</a></li>
                    <li><a class="hover:underline" href="{{ route('users.show', auth()->user()->id) }}">View Your Profile</a></li>
                </ul>

            </div>
            <x-divide />
            <div class="hover:bg-gray-400/75 w-fit p-1 duration-200">
                <form class="" action="{{ route('logout.destroy') }}" method="Post">
                    @csrf
                    <button type="submit" class="cursor-pointer">Logout</button>
                </form>
            </div>
        </div>
    </div>
