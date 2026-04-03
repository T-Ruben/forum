<?php

use Livewire\Component;
use App\Models\User;

new class extends Component
{
    public User $user;
    public string $tab = 'posts';

    public function mount($user) {
        $this->user = $user;
    }

};
?>


<div>
    <div class="mb-6">
        <p class="text-xl">{{ $user->display_name }}</p>
        <p class="text-sm"><span>{{ $user->profile_summary }}</span></p>
        <hr>
        <div class="select-none">
            <button class="border-x px-1 mt-1 hover:text-gray-400 duration-200 cursor-pointer text-shadow-lg/40"
                wire:click="$set('tab', 'posts')">
                Posts
            </button>
            <button class="border-x px-1 mt-1 hover:text-gray-400 duration-200 cursor-pointer text-shadow-lg/40"
                wire:click="$set('tab', 'activity')">
                Recent Activity
            </button>
        </div>
    </div>
    @if ($tab === 'posts')
        <livewire:livewire.profile.posts :user="$user" />
        @else
        <livewire:livewire.profile.activity :user="$user" />
    @endif
</div>
