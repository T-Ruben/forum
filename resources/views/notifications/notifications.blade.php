 <x-layout>
    <x-header />

    <x-main>
        {{ $notifications->links() }}
        <h2 class="text-2xl font-bold border-b-2 border-black  p-2 relative dark:bg-blue-950">Notifications</h2>

        <ul>
           @forelse ($notifications as $notification)
            @includeIf('notifications.types.' . class_basename($notification->type), [
                'notification' => $notification,
            ])

            @empty
            <p class="flex justify-center w-full text-lg mt-3">No notifications</p>
           @endforelse
        </ul>
        {{ $notifications->links() }}
    </x-main>

    <x-footer />
 </x-layout>
