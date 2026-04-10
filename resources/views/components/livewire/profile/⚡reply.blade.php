<?php

use Livewire\Component;
use App\Models\Post;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\User;
use App\Actions\CreatePostAction;
use App\Actions\DeletePostAction;
use App\Actions\UpdatePostAction;

new class extends Component
{
    public User $user;
    public Post $post;
    public $amount = 3;
    public $replyTo = null;
    public $editPost = null;
    public $content = null;

    #[Url]
    public $highlight = null;

    public function mount($user, $post) {
        $this->user = $user;
        $this->post = $post;

        $this->checkAndExpand();
    }

        public function setReply($id) {
        $this->editPost = null;

        $this->replyTo = (int) $id;
    }

    public function setEdit($id) {
        $post = Post::findOrFail($id);
        $this->replyTo = null;

        Gate::authorize('update', $post);

        $this->editPost = $post->id;
        $this->content = $post->content;
    }

    public function submit()
    {
        $this->validate([
            'content' => ['required', 'string', 'min:1', 'max:1000'],
        ]);

        if ($this->editPost) {
            $post = Post::findOrFail($this->editPost);

            Gate::authorize('update', $post);

            app(UpdatePostAction::class)->execute(
                    $post,
                    ['content' => $this->content],
                    'profile'
                );

        } else {
            app(CreatePostAction::class)->execute(auth()->user(), [
                'content' => $this->content,
                'profile_user_id' => $this->user->id,
                'parent_id' => $this->replyTo,
            ]);

            $this->amount++;
        }

        $this->reset(['content', 'replyTo', 'editPost']);

        $this->dispatch('$refresh');
    }

    public function delete(Post $post)
    {
        Gate::authorize('delete', $post);

        app(DeletePostAction::class)->execute($post);
    }

    public function checkAndExpand()
    {
        if (!$this->highlight) return;

        if ($this->highlight == $this->post->id) {
            $this->amount = 10;
            return;
        }

        $repliesIds = $this->post->replies->pluck('id')->toArray();
        $index = array_search($this->highlight, $repliesIds);

        if ($index !== false) {
            $this->amount = max($this->amount, $index + 1);
        }
    }

    public function loadMore()
    {
        $this->amount += 10;
    }

    public function cancel() {
        $this->reset(['content', 'replyTo', 'editPost']);
    }

    public function render()
    {
        if ($this->post->relationLoaded('replies')) {
            $replies = $this->post->replies
                ->take($this->amount);
        } else {
            $replies = $this->post->replies()
                ->with('user')
                ->take($this->amount)
                ->get();
        }

        return view('components.livewire.profile.⚡reply', [
            'replies' => $replies
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
                    <button wire:click="editPost('{{ $reply->id }}')"
                        class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                        Edit
                    </button>
                @endcan

                <button wire:click="setReply('{{ $reply->id }}')"
                    class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                    Reply
                </button>
            </div>
        </div>
            @if ($replyTo === $reply->id || $editPost === $reply->id)
                <form wire:submit.prevent="submit" class="w-full formReload" id="postForm">

                    <textarea
                        id="content"
                        wire:model.defer="content"
                        rows="6"
                        maxlength="1000"
                        class="w-full p-2 bg-gray-200 text-black resize-none border border-gray-600 outline-none"
                        placeholder="Write your post..."
                    ></textarea>

                    @error('content')
                        <p class="text-red-500">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-between gap-5">
                        <button wire:click="cancel()"
                            class="text-white bg-red-700 hover:dark:bg-red-900/80 block border rounded-md p-1">
                            Cancel
                        </button>
                        <button type="submit"
                            class="text-white dark:bg-blue-950 hover:dark:bg-blue-900/80 block border rounded-md p-1">
                            Post Reply
                        </button>
                    </div>
                </form>
            @endif
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
