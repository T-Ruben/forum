 <x-layout>
    <x-header />

    <x-main>
        {{ $notifications->links() }}
        <div class="dark:bg-blue-950 flex items-center justify-between pr-2 border-b-2 border-black">
            <h3 class="text-2xl font-bold p-2 relative ">Notifications</h3>
            <form action="{{ route('notifications.read.all') }}" method="POST">
                @csrf
                <button class="cursor-pointer hover:underline">Mark all as read</button>
            </form>
        </div>

        <ul class="h-full">
           @forelse ($notifications as $notification)
            @includeIf('notifications.types.' . class_basename($notification->type), [
                'notification' => $notification,
                'variant' => 'page'
            ])

            @empty
            <p class="flex justify-center w-full text-lg mt-3">No notifications</p>
           @endforelse
        </ul>
        {{ $notifications->links() }}
    </x-main>

    <x-footer />
 </x-layout>
