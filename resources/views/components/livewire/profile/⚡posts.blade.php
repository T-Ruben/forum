<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use App\Controllers\UserController;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Actions\CreatePostAction;
use App\Actions\UpdatePostAction;
use App\Actions\DeletePostAction;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\RateLimiter;

new class extends Component
{
    use WithPagination;

    public User $user;

    public $moreLess = null;
    public $replyTo = null;
    public $editPost = null;

    #[Validate('required|string|min:1|max:1000')]
    public $content = null;

    #[On('reset-page')]
    public function handleReset() {
        $this->resetPage();
    }

    #[On('post-created')]
    public function refreshPosts()
    {
        $this->dispatch('$refresh');
    }

    public function mount($user) {
        $this->user = $user;
    }

    public function setReply($id) {
        $this->editPost = null;

        $this->content = null;
        $this->replyTo = (int) $id;
    }

    public function setEdit($id) {
        $post = Post::findOrFail($id);
        $this->replyTo = null;

        Gate::authorize('update', $post);

        $this->editPost = $post->id;
        $this->content = $post->content;
    }

    protected function createPost()
    {
        Gate::authorize('create', Post::class);

        $this->content = trim(strip_tags($this->content));

        $key = 'post-limit' . auth()->id();

        if(RateLimiter::tooManyAttempts($key, $maxAttempts = 1)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('content', "Please wait {$seconds} seconds before posting again.");
            return;
        }

        $this->validate();

        app(CreatePostAction::class)->execute(
            auth()->user(),
            [
                'content' => $this->content,
                'profile_user_id' => $this->user->id,
                'parent_id' => $this->replyTo,
            ]
        );

        RateLimiter::hit($key, $decaySeconds = 3);

        $this->reset(['content', 'replyTo', 'editPost']);

        $this->dispatch('reply-created', parentId: $this->replyTo);
        $this->dispatch('$refresh');
    }

    protected function updatePost()
    {
        $post = Post::findOrFail($this->editPost);

        Gate::authorize('update', $post);

        $this->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        app(UpdatePostAction::class)->execute(
            $post,
            ['content' => $this->content],
            'profile'
        );

        $this->reset(['content', 'editPost']);

        $this->dispatch('post-updated');
    }

    public function submit()
    {
        if ($this->editPost) {
            $this->updatePost();
        } else {
            $this->createPost();
        }
    }

    public function delete(Post $post)
    {
        Gate::authorize('delete', $post);

        app(DeletePostAction::class)->execute($post);
    }

    public function getReplyToPostProperty() {
        if(!$this->replyTo) return null;

        return Post::with('user')->find($this->replyTo);
    }

    public function cancel() {
        $this->reset(['content', 'replyTo', 'editPost']);
    }

    public function render()
    {
        return view('components.livewire.profile.⚡posts', [
            'posts' => $this->user->profilePosts()
                ->whereNull('parent_id')
                ->with(['user', 'parent', 'replies.user'])
                ->withCount('replies')
                ->latest()
                ->paginate(10)
        ]);
    }

};
?>

<div>
    <div class="post-content break-words">
        @if ($this->replyToPost)
            <div class="mb-4 p-3 border rounded text-sm border-gray-600 bg-gray-300/20 text-black">
                <p class="flex justify-between border-b">
                    <span>Replying to <a href="#post-{{ $this->replyToPost?->id }}"
                        class="hover:underline font-semibold duration-200">{{ $this->replyToPost?->user->display_name }}</a></span>
                    <button wire:click="cancel()"
                        class="formReload hover:text-red-500/75 duration-200">@include('icons.cancel')</button>
                </p>

                <div class="relative w-full">
                    <input type="checkbox" id="load-more-{{ $this->replyToPost?->id }}" class="peer hidden">

                    <div class=" whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none break-words overflow-hidden">
                        <span class="">{{ $this->replyToPost?->content }}</span>
                    </div>

                    @if (strlen($this->replyToPost?->content) > 300)
                    <label for="load-more-{{ $this->replyToPost?->id }}"
                        class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                        Read more...
                    </label>

                    <label for="load-more-{{ $this->replyToPost?->id }}"
                        class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                        Show less
                    </label>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="mb-3 flex gap-3 px-1">
        <div class="hidden sm:flex w-20 h-20 shrink-0 border-1">
            @auth
            <a href="{{ route('users.show', auth()->user()) }}" class="w-full h-full">
                <img src="{{ asset(auth()->user()?->profile_image_url) }}" class="w-full h-full object-cover"
                    alt="{{ auth()->user()->name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
            </a>
            @endauth
            @guest
                <a href="{{ route('login')}}">
                <img src="{{ asset('images/default-avatar.png') }}" class="w-20 h-20 object-cover"
                    alt="Guest's profile image" data-pin-nopin="true">
                </a>
            @endguest
        </div>

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
                @if ($this->editPost || $this->replyTo)
                <button type="button"
                    wire:click="cancel()"
                    class="text-white  bg-red-700 hover:dark:bg-red-900/80 block border rounded-md p-1 select-none cursor-pointer">
                    Cancel Edit
                </button>
                @endif
                <button type="submit"
                    class="text-white dark:bg-blue-950 hover:dark:bg-blue-900/80 block border rounded-md p-1 select-none cursor-pointer">
                    Post Reply
                </button>
            </div>
        </form>
    </div>

    <div class="bg-gray-300/60 text-black p-1 sm:p-2 w-full max-w-full overflow-x-hidden">
        @forelse ($posts as $post)
        @if (!$post->parent)
            @if ($post->trashed())
                <div class=" mb-3 w-full">[Deleted]</div>
            @else
            <div class="flex shrink-0 gap-3" wire:key="post-{{ $post->id }}">

                <div class="w-10 sm:w-20 h-10 sm:h-20 flex shrink-0 border-1">
                    <a href="{{ $post->user?->user_url }}" class="w-full h-full">
                        <img src="{{ asset($post->user->profile_image_url) }}" class="w-full h-full object-cover"
                            alt="{{ $post->user?->display_name ?? 'Deleted Member' }}'s profile image" data-pin-nopin="true">
                    </a>
                </div>

                <div class="overflow-hidden w-full min-w-0 mb-4 mt-2">
                    <div id="post-{{ $post->id }}">
                        <div>
                            <a href="{{ $post->user?->user_url }}"
                                class="hover:text-black/70 duration-200 hover:underline"><strong>{{ $post->user->display_name }}</strong></a>
                            <div class="post-content whitespace-pre-line break-all md:break-words pb-10">{!! $post->content !!}</div>
                        </div>
                        <div class="flex justify-between">
                            <small class="text-gray-300"><x-time-display :time="$post->updated_at" :createdAt="$post->created_at" :updatedAt="$post->updated_at" /></small>

                            <div class="flex gap-5">
                                <x-actions.delete-button :action="route('profile.post.destroy', $post)" :model="$post" />

                                @can('update', $post)
                                    <button wire:click="setEdit({{ $post->id }});
                                            $dispatch('scroll-to-form');"
                                        class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                                        Edit
                                    </button>
                                @endcan

                                <button wire:click="setReply({{ $post->id }});
                                        $dispatch('scroll-to-form');"
                                    class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                                    Reply
                                </button>
                            </div>

                        </div>
                    </div>

                    <div>
                        <div >
                            <livewire:livewire.profile.reply :post="$post" :user="$user" :key="'post-'.$post->id" />
                        </div>
                    </div>
                </div>
            </div>
            @endif


            <hr class="mb-3">
        @endif
        @empty
            <p>No posts on this profile yet.</p>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $posts->links() }}
    </div>
</div>
