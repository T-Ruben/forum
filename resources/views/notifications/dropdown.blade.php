<div class="absolute bg-gray-800 z-50 border border-black">
    <div class="flex justify-between p-1 border-b border-gray-400">
        <div class="flex gap-2">
            <span class="font-bold">Notifications</span>
            @if ($user->total >= 1)
                <span class="flex items-center justify-center w-8 h-6 rounded-lg text-xs
                    font-bold text-white border border-black bg-red-600 text-shadow-lg/25 select-none">
                    {{ $user->total }}
                </span>
            @endif
        </div>
        <form action="{{ route('notifications.read.all') }}" method="POST">
            @csrf
            <button class="cursor-pointer hover:underline active:underline">Mark all as read</button>
        </form>
    </div>

    <ul class="max-h-80 overflow-y-auto w-75 sm:w-80 ">
    @forelse ($notifications as $notification)
            @include('notifications.types.' . class_basename($notification->type), [
                'variant' => 'dropdown',
            ])

    @empty
        <p class="text-center py-3 text-sm">
            No notifications
        </p>
    @endforelse
    </ul>
    <a href="{{ route('notifications.index', Auth::user()) }}" class="">
        <div class="flex justify-center bg-gray-900 hover:text-gray-900 active:text-gray-900
            hover:bg-gray-300 active:bg-gray-300 duration-200">
                View all
        </div>
    </a>
</div>
