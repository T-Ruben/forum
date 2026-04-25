<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Livewire\WithPagination;
use App\Models\Conversation;

new class extends Component
{
    use WithPagination;

    public $search = '';
    public $conversationId;
    public $conversation;

    public function with(): array
    {
        $users = [];

        if (strlen($this->search) >= 2) {
            $users = User::where('name', 'like', '%' . $this->search . '%')
                ->where('id', '!=', auth()->id())
                ->limit(5)
                ->get();
        }

        return [
            'users' => $users,
            'messages' => Message::where('conversation_id', $this->conversationId)
                            ->latest()
                            ->paginate(10),
        ];
    }
};
?>

<div class="relative w-full sm:w-100 mb-2 border-l-1 p-1 flex gap-2">
    <span class="shrink-0">Invite a friend: </span>

    <div class="relative flex-grow">
            <input
        wire:model.live.debounce.300ms="search"
        type="text"
        placeholder="Search users..."
        class="border-1 bg-white rounded text-black text-lg pl-2 w-full"
    >
    @if($search)
        <button
            wire:click="$set('search', '')"
            type="button"
            class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 font-bold text-2xl
                leading-none hover:cursor-pointer"
        >
            &times;
        </button>
    @endif

    @error('search')
    <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror

    @if (session('success'))
        <div class="text-green-500 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(count($users) > 0)
        <ul class="absolute z-10 w-2/3 sm:w-full bg-white shadow-lg border text-black">
            @foreach($users as $user)
                <li class="p-2 hover:bg-gray-100 cursor-pointer flex justify-between">
                    <span class="truncate">{{ $user->name }}</span>
                    <form action="{{ route('conversation.invite', $conversation) }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <button type="submit"
                            class="text-lg text-white rounded dark:bg-blue-900 border-black border-fray-400 px-1
                            hover:dark:bg-blue-900/80 duration-200 cursor-pointer">
                            Invite
                        </button>
                    </form>

                </li>
            @endforeach
        </ul>
    @endif
    </div>

</div>
