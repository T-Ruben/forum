<div class="absolute bg-gray-800 z-50 border border-black">
    <div class="flex justify-between p-1 border-b border-gray-400">
        <div>
            <span class="font-bold">Notifications</span>
        </div>
        <form action="{{ route('notifications.read.all') }}" method="POST">
            @csrf
            <button class="cursor-pointer hover:underline">Mark all as read</button>
        </form>
    </div>

    <ul class="max-h-80 overflow-y-auto w-80 ">
    @forelse ($notifications as $notification)
            @include('notifications.types.' . class_basename($notification->type))

    @empty
        <p class="text-center py-3 text-sm">
            No notifications
        </p>
    @endforelse
    </ul>

    <div class="flex justify-center bg-gray-900">
        <span>View all</span>
    </div>
</div>
