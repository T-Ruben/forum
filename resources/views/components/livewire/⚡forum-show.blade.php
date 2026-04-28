<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Forum;

new class extends Component
{
    use WithPagination;

    public Forum $forum;

    public $sort = 'created_at';
    public $direction = 'desc';

    public function setSort($column) {
        if($this->sort === $column) {
            $this->direction = $this->direction === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sort = $column;
            $this->direction = 'desc';
        }
        $this->resetPage();
    }

    public function with() {
        $sortField = match($this->sort) {
            'title' => 'title',
            'replies' => 'posts_count',
            'activity' => 'updated_at',
            default => 'created_at',
        };

        return [
            'threads' => $this->forum->threads()
                ->with(['user', 'posts', 'latestPost', 'latestPost.user'])
                ->withCount('posts')
                ->orderBy($sortField, $this->direction)
                ->paginate(20),
        ];
    }
};
?>

<div class="w-full">
    <div>
        {{ $threads->links() }}
    </div>

    <table class="table-fixed w-full">
        <thead class=" bg-blue-950 text-md sm:text-lg w-full h-auto">
            <tr class="text-center w-full">
                <th class="hover:bg-blue-900/50 active:bg-blue-900/50 duration-200 cursor-pointer transition-colors py-2 w-2/5"
                    wire:click="setSort('title')">
                    Title @if($sort === 'title') {{ $direction === 'asc' ? '↑' : '↓' }} @endif</th>

                <th class="hover:bg-blue-900/50 active:bg-blue-900/50 duration-200 cursor-pointer transition-colors py-2"
                    wire:click="setSort('created_at')">
                    Created @if($sort === 'created_at') {{ $direction === 'asc' ? '↑' : '↓' }} @endif</th>

                <th class="hover:bg-blue-900/50 active:bg-blue-900/50 duration-200 cursor-pointer transition-colors py-2 hidden sm:table-cell"
                    wire:click="setSort('replies')">
                    Replies @if($sort === 'replies') {{ $direction === 'asc' ? '↑' : '↓' }} @endif</th>

                <th class="hover:bg-blue-900/50 active:bg-blue-900/50 duration-200 cursor-pointer transition-colors py-2"
                    wire:click="setSort('activity')">
                    Activity @if($sort === 'activity') {{ $direction === 'asc' ? '↑' : '↓' }} @endif</th>
            </tr>
        </thead>

        <tbody wire:loading.class="opacity-50 transition-opacity">
        @foreach ($threads as $thread)
            <tr class="text-lg ml-2 border-b-1">
                <td class="min-w-0 flex">
                    <div class="my-2 w-12 h-12 ml-1 mr-2 border shadow-xs shadow-black text-black flex shrink-0">
                        <a href="{{ route('users.show', $thread->user) }}">
                        <img src="{{ $thread->user->profile_image_url }}"
                            class="w-12 h-12 object-cover"
                            alt="{{ $thread->user->display_name ?? 'Deleted Member' }}'s profile image">
                        </a>
                    </div>
                    <div class="min-w-0 flex flex-col w-full truncate justify-between">
                        <div class="">
                            <x-link :active="true" href="{{ route('threads.show', [$thread->id, $thread->slug]) }}"
                                title="{{ $thread->title }}">
                                {{ $thread->title }}
                            </x-link>
                        </div>
                        <span class="flex flex-wrap truncate w-full text-sm">
                            <x-link :active="false" href="{{ route('users.show', $thread->user) }}" title="Thread starter">
                                    <span class="mr-1 text-gray-200">{{ $thread->user->display_name }}, </span>
                            </x-link>
                            <x-link :active="false" href="{{ route('threads.show', [$thread->id, $thread->slug]) }}">
                                <span class="text-gray-300/75">{{ $thread->created_at->format('M d, Y') }}</span>
                            </x-link>
                        </span>
                    </div>
                </td>
                <td class="text-center text-sm"><x-time-display :time="$thread->created_at" /></td>
                <td class="text-center text-sm hidden sm:table-cell">Replies: {{ $thread->posts_count ?? '0' }}</td>
                <td class="text-center text-sm">
                    @if ($thread->latestPost?->user)
                        <a href="{{ route('users.show', $thread->latestPost->user) }}">
                            <span class="block hover:underline">{{ $thread->latestPost->user->display_name }}</span>
                        </a>

                        <x-time-display :time="$thread->latestPost?->updated_at" />

                    @else
                        <span class="block">No Activity</span>
                    @endif
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>

    <div>
        {{ $threads->links() }}
    </div>
</div>
