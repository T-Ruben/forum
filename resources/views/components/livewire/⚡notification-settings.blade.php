<?php

use Livewire\Component;

new class extends Component
{
    public array $mutedNotifications = [];
    public bool $muteAll = false;

    public array $notificationTypes = [
        'profilePosts' => 'Profile Posts',
        'threadPosts' => 'Thread Posts',
        'convMessages' => 'Conversation Messages',
        'convInvites' => 'Conversation Invites',
        'follow' => 'Follow Notifications',
    ];

    public function mount() {
        $saved = auth()->user()->muted_notifications ?? [];

        $this->muteAll = $saved['all'] ?? false;
        $this->mutedNotifications = $saved['types'] ?? [];
    }

    public function updatedMuteAll($value) {
        if ($value === true) {
            $this->mutedNotifications = array_keys($this->notificationTypes);
        }
    }

    public function resetPreferences() {
        $this->muteAll = false;
        $this->mutedNotifications = [];
    }

    public function saveSettings() {
        auth()->user()->update([
            'muted_notifications' => [
                    'all' => $this->muteAll,
                    'types' => $this->muteAll ? array_keys($this->notificationTypes) : $this->mutedNotifications,
                ],
        ]);

        session()->flash('message', 'Preferences updated successfully!');
    }

    public function render() {
        return view('components.livewire.⚡notification-settings');
    }
};
?>

<div>
    <h2 class="text-xl font-bold mb-4">Notification Preferences</h2>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded text-sm">
            {{ session('message') }}
        </div>
    @endif
    <form wire:submit="saveSettings">
        <div class="border-b border-gray-200 mb-2">
            <div class="flex items-center justify-between pb-3">
                <div>
                    <label for="mute-all" class="font-medium ">Mute All Notifications</label>
                    <p class="text-xs text-gray-400">Silence all incoming real-time and database alerts</p>
                </div>
                <input
                    id="mute-all"
                    type="checkbox"
                    wire:model.live="muteAll"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                />
            </div>
            <div class="flex justify-between pb-3">
                <div>
                    <p class="text-sm font-medium">Reset Preferences</p>
                    <p class="text-xs text-gray-500">Clear all muted categories and restore standard alerts.</p>
                </div>
                <button
                    wire:click="resetPreferences"
                    class="p-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
                >Reset</button>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($notificationTypes as $key => $label)
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="notif-{{ $key }}"
                            type="checkbox"
                            value="{{ $key }}"
                            wire:model="mutedNotifications"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        />
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="notif-{{ $key }}" class="font-medium text-gray-300">{{ $label }}</label>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            <button
                type="submit"
                class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none"
            >
                Save Preferences
            </button>
        </div>
    </form>
</div>
