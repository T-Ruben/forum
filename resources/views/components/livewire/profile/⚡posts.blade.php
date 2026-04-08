<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;
use App\Controllers\UserController;
use Livewire\WithPagination;
use Livewire\Attributes\On;

new class extends Component
{
    use WithPagination;

    public User $user;

    public $moreLess = null;
    public $replyTo = null;
    public $editPost = null;

    #[On('reset-page')]
    public function handleReset() {
        $this->resetPage();
    }

    protected $queryString = [
        'replyTo' => ['as' => 'reply_to'],
        'editPost' => ['as' => 'edit_post']
    ];

    public function mount($user) {
        $this->user = $user;


        if($this->editPost) {
            $this->editPost = Post::where('profile_user_id', $user->id)
                ->findOrFail($this->editPost);

            Gate::authorize('update', $this->editPost);
        }
        elseif($this->replyTo) {
            $this->replyTo = Post::where('profile_user_id', $user->id)
                ->findOrFail($this->replyTo);
        }
    }

    public function getPostsProperty()
    {
        return $this->user->profilePosts()
            ->whereNull('parent_id')
            ->with(['user', 'parent', 'replies', 'replies.user'])
            ->withCount('replies')
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('components.livewire.profile.⚡posts', [
            'posts' => $this->user->profilePosts()
                ->whereNull('parent_id',)
                ->with(['user', 'parent'])
                ->withCount('replies')
                ->latest()
                ->paginate(10)
        ]);
    }

};
?>

<div>
    <div class="post-content break-words">
                @if ($replyTo)
                    <div class="mb-4 p-3 border rounded text-sm border-gray-600 text-black">
                        <p class="flex justify-between border-b">
                            <span>Replying to <a href="#post-{{ $replyTo->id }}"
                                class="hover:underline font-semibold duration-200">{{ $replyTo->user->display_name }}</a></span>
                            <a href="{{ route('users.show', ['user' => $user->id,  'page' => request('page')]) }}"
                                class="formReload hover:text-red-500/75 duration-200">@include('icons.cancel')</a>
                        </p>

                        <div class="relative w-full">
                            <input type="checkbox" id="load-more-{{ $replyTo->id }}" class="peer hidden">

                            <div class=" whitespace-pre-line line-clamp-5 peer-checked:line-clamp-none break-words overflow-hidden">
                                <span class="">{{ $replyTo->content }}</span>
                            </div>

                            @if (strlen($replyTo->content) > 300)
                            <label for="load-more-{{ $replyTo->id }}"
                                class="select-none cursor-pointer text-blue-500 hover:underline mt-2 block peer-checked:hidden">
                                Read more...
                            </label>

                            <label for="load-more-{{ $replyTo->id }}"
                                class="select-none cursor-pointer text-blue-500 hover:underline mt-2 hidden peer-checked:block">
                                Show less
                            </label>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="mb-3 flex gap-3">
                <div class="w-20 h-20 flex shrink-0 border-1">
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

                @php
                    $isEdit = isset($editPost);
                    $action = $isEdit ? route('profile.post.update', $editPost) : route('user.posts.store', $user);
                @endphp

                <form action="{{ $action }}" method="POST" class="formReload w-full" id="postForm">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $replyTo?->id ?? null }}">
                    <input type="hidden" name="profile_user_id" value="{{ $user->id }}">

                    @if ($isEdit)
                        @method('PUT')
                    @endif

                    <textarea
                        id="content"
                        name="content"
                        rows="6"
                        class="w-full p-2 bg-gray-200 text-black resize-none overflow-hidden border border-gray-600
                        outline-none"
                        placeholder="Write your post...">{{ old('content', $isEdit ? $editPost->content : '') }}</textarea>
                    <div>

                    </div>
                    <div class="my-auto flex-2 justify-center">
                        @error('content')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    @if($isEdit)
                        <a href="{{ route('users.show', [$user, 'page' => request('page')]) }}"
                        class="text-gray-500 mr-4">
                            Cancel Edit
                        </a>
                    @endif

                    <button type="submit"
                        class="text-white dark:bg-blue-950 hover:dark:bg-blue-900/80 cursor-pointer duration-200 ml-auto block border rounded-md p-1">
                        Post Reply
                    </button>

                </form>
            </div>

            <div class="bg-gray-300/60 text-black p-2 w-full max-w-full overflow-x-hidden">
                @forelse ($posts as $post)
                @if (!$post->parent)
                    <div class="flex shrink-0 gap-3">
                    @if ($post->trashed())
                        <p class=" mb-3">[Deleted]</p>
                    @else
                        <div class="w-20 h-20 flex shrink-0 border-1">
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
                                            <a href="{{ route('users.show', ['user' => $user->id, 'edit_post' => $post->id, 'page' => request('page')]) }}"
                                                class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                                                Edit
                                            </a>
                                        @endcan

                                        <a href="{{ route('users.show', ['user' => $user->id, 'reply_to' => $post->id, 'page' => request('page')]) }}"
                                            class="cursor-pointer dark:text-blue-900 hover:dark:text-blue-900/75 hover:underline duration-200 font-semibold">
                                            Reply
                                        </a>
                                    </div>

                                </div>
                            </div>

                            <div>



                                <div >
                                    <livewire:livewire.profile.reply :post="$post" :user="$user" :key="'post-'.$post->id" />
                                </div>



                                {{-- @if ($post->replies->count() > 3)
                                    <label for="load-replies-{{ $post->id }}" class="select-none cursor-pointer text-blue-500 hover:underline block peer-checked:hidden">
                                        Show more...
                                    </label>

                                    <label for="load-replies-{{ $post->id }}" class="select-none cursor-pointer text-blue-500 hover:underline hidden peer-checked:block">
                                        Show less...
                                    </label>
                                @endif --}}
                            </div>
                        </div>
                    @endif
                    </div>

                    <div>

                    </div>

                    <hr class="mb-3">
                @endif
                @empty
                    <p>No posts on this profile yet.</p>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $this->posts->links() }}
            </div>
</div>
