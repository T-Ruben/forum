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

        <div class="flex">
            <aside class="w-1/3 h-fit border-x border-white/50 p-1 m-1">
                <ul class="">
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer">
                        All Notifications</li>
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer">
                        Profile Posts</li>
                    <li class="p-1 hover:bg-gray-600 cursor-pointer">
                        Thread Posts</li>
                    <hr class="h-px my-2 border-t-0 bg-transparent bg-gradient-to-r from-transparent via-gray-100 to-transparent" />
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer">
                        Conversation Posts</li>
                    <li class="p-1 border-b border-gray-400/50 hover:bg-gray-600 cursor-pointer">
                        Conversation Invites</li>
                </ul>
            </aside>
            <ul class="h-full w-2/3">
                @forelse ($notifications as $notification)
                    @includeIf('notifications.types.' . class_basename($notification->type), [
                        'notification' => $notification,
                        'variant' => 'page'
                    ])

                    @empty
                    <p class="flex justify-center w-full text-lg mt-3">No notifications</p>
                @endforelse
            </ul>
        </div>
        {{ $notifications->links() }}
    </x-main>

    <x-footer />
 </x-layout>
