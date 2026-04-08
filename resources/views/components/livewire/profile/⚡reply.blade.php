<?php

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\Url;
use App\Models\User;

new class extends Component
{
    public User $user;
    public Post $post;
    public $amount = 3;

    #[Url]
    public $highlight = null;

    public function mount($post, $user) {
        $this->post = $post;
        $this->user = $user;

        $this->checkAndExpand();
    }


public function checkAndExpand()
    {
        if (!$this->highlight) return;

        if ($this->highlight == $this->post->id) {
            $this->amount = 10;
            return;
        }

        $repliesIds = $this->post->replies()->pluck('id')->toArray();
        $index = array_search($this->highlight, $repliesIds);

        if ($index !== false) {
            $this->amount = max($this->amount, $index + 1);
        }
    }

    public function loadMore()
    {
        $this->amount += 10;
    }
    public function render() {
        return view('components.livewire.profile.⚡reply', [
            'replies' => $this->post->replies()
                ->with('user')
                ->take($this->amount)
                ->get()
        ]);
    }

};
?>
<div>
@foreach ($replies as $reply)
<div class="flex shrink-0 gap-3 border-b-1 rounded-b-md bg-gray-300/25 px-1 pt-1 {{ $highlight === $reply->id ?
    'bg-gray-700/25 border-b border-x border-indigo-700' : '' }}"
    id="post-{{ $reply->id }}">
    @if ($reply->trashed())
        <p class=" my-3">[Deleted]</p>
    @else
    <div class="w-16 h-16 flex shrink-0 border-1">
        <a href="{{ $reply->user?->user_url }}" class="w-full h-full">
            <img src="{{ asset($reply->user->profile_image_url) }}" class="w-full h-full object-cover"
                alt="{{ $reply->user?->display_name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
        </a>
    </div>
    <div class="overflow-hidden w-full min-w-0 mb-4 mt-2">
        <div>
            <div>
                <a href="{{ $reply->user?->user_url }}"
                    class="hover:text-black/70 duration-200 hover:underline"><strong>{{ $reply->user?->display_name }}</strong></a>
                <div class="post-content whitespace-pre-line break-all md:break-words">{!! $reply->content !!}</div>
            </div>
        </div>
        <div class="flex justify-between text-md">
            <small class="text-gray-300"><x-time-display :time="$reply->updated_at" :createdAt="$reply->created_at" :updatedAt="$reply->updated_at"/></small>
            <div class="flex gap-5">
                <x-actions.delete-button :action="route('profile.post.destroy', $reply)" :model="$reply" />

                @can('update', $reply)
                    <a href="{{ route('users.show', ['user' => $user->id, 'edit_post' => $reply, 'page' => request('page')]) }}"
                        class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                        Edit
                    </a>
                @endcan

                <a href="{{ route('users.show', ['user' => $user->id, 'reply_to' => $reply->id, 'page' => request('page')]) }}"
                    class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endforeach

@if($post->replies_count > $amount)
    <button
        wire:click="loadMore"
        class="text-blue-500 text-xs hover:underline"
        wire:loading.attr="disabled"
    >
        View more replies...
    </button>
@endif
</div>

{{-- @if ($reply->recursiveReplies->count() > 0)
    <div class="nested-replies">
        @foreach ($reply->recursiveReplies as $subReply)
            @include('components.reply', ['reply' => $subReply, 'depth' => $depth + 1])
        @endforeach
    </div>
@endif --}}
