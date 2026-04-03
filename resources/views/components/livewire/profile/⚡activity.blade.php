<?php

use Livewire\Component;
use App\Models\User;
use App\Models\Post;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

new class extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public User $user;

    public function mount($user){
        $this->user = $user;
    }

    public function getActivitiesProperty(){
        $posts = $this->user->posts()->with('user', 'profileOwner', 'parent', 'thread', 'thread.forum')->get();
        $threads = $this->user->threads()->with('forum')->get();

        $postActivities = $posts->map(function ($post) {
            if($post->thread_id) {
                if(!$post->parent_id) {
                    return [
                        'type' => 'thread_post',
                        'model' => $post,
                        'created_at' => $post->created_at
                    ];
                }

                if($post->parent_id) {
                    return [
                        'type' => 'thread_reply',
                        'model' => $post,
                        'created_at' => $post->created_at
                    ];
                }
            }

            if($post->profile_user_id) {
                if(!$post->parent_id && $post->profile_user_id !== $post->user_id) {
                    return [
                        'type' => 'profile_post',
                        'model' => $post,
                        'created_at' => $post->created_at
                    ];
                }

                if($post->parent_id) {
                    return [
                        'type' => 'profile_reply',
                        'model' => $post,
                        'created_at' => $post->created_at
                    ];
                }

                if($post->profile_user_id === $post->user_id) {
                    return [
                        'type' => 'own_profile_post',
                        'model' => $post,
                        'created_at' => $post->created_at
                    ];
                }
            }

            return null;
        })->filter();

        $threadActivities = $threads->map(function($thread) {
            return [
                'type' => 'thread',
                'model' => $thread,
                'created_at' => $thread->created_at
            ];
        });

        $activities = $postActivities->merge($threadActivities)->sortByDesc('created_at')->values();

        $page = $this->getPage();
        $perPage = 20;

        return new LengthAwarePaginator(
            $activities->forPage($page, $perPage),
            $activities->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }



};
?>
<div>
    {{ $this->activities->links() }}
   <ul class=" inset-shadow-sm/50 p-2 my-2">
    @foreach ($this->activities as $activity)
        <li class="flex flex-col truncate py-1 border-b border-black/50">

        {{-- 1 --}}
            @if ($activity['type'] === 'own_profile_post')
                <span><a href="{{ route('users.show', $this->user) }}" class="hover:underline font-semibold">
                    {{ $this->user->display_name }}</a>
                    created a post on their profile.
                </span>

                {{-- 2 --}}
            @elseif($activity['type'] === 'profile_post')
                <span><a href="{{ route('users.show', $this->user) }}" class="hover:underline font-semibold">
                    {{ $this->user->display_name }}</a>
                    posted on <a href="{{ route('users.show', $activity['model']->profileOwner) }}" class="hover:underline font-semibold">
                        {{ $activity['model']->profileOwner->display_name }}</a>'s profile.</span>

                    {{-- 3 --}}
            @elseif ($activity['type'] === 'profile_reply')
                <span><a href="{{ route('users.show', $this->user) }}" class="hover:underline font-semibold">
                    {{ $this->user->display_name }}</a>
                    replied to a post on <a href="{{ route('users.show', $activity['model']->profileOwner) }}" class="hover:underline font-semibold">
                        {{ $activity['model']->profileOwner->display_name }}</a>'s profile.</span>

                    {{-- 4 --}}
            @elseif ($activity['type'] === 'thread_post')
                <span><a href="{{ route('users.show', $this->user) }}" class="hover:underline font-semibold">
                    {{ $this->user->display_name }}</a>
                    posted on Thread: <a href="{{ route('threads.show', [$activity['model']->thread, $activity['model']->thread->slug]) }}"
                        class="hover:underline font-semibold">
                        {{ $activity['model']->thread->title }}</a>.</span>

                    {{-- 5 --}}
            @elseif($activity['type'] === 'thread_reply')
                <span><a href="{{ route('users.show', $this->user) }}" class="hover:underline font-semibold">
                    {{ $this->user->display_name }}</a>
                    replied to a post on Thread: <a href="{{ route('threads.show', [$activity['model']->thread, $activity['model']->thread->slug]) }}"
                        class="hover:underline font-semibold">
                        {{ $activity['model']->thread->title }}</a>.</span>

                    {{-- 6 --}}
            @elseif($activity['type'] === 'thread')
                <span><a href="{{ route('users.show', $this->user) }}" class="hover:underline font-semibold">
                    {{ $this->user->display_name }}</a>
                    created a new Thread: <a href="{{ route('threads.show', [$activity['model'], $activity['model']->slug]) }}"
                        class="hover:underline font-semibold">
                        {{ $activity['model']->title }}</a>
                        in Forum:
                        <a href="{{ route('forums.show', [$activity['model']->forum, $activity['model']->forum->slug]) }}"
                            class="hover:underline font-semibold">
                            {{ $activity['model']->forum->title }}
                        </a>
                    </span>
            @endif
            <span class="text-sm text-gray-500">{{ $activity['created_at']->diffForHumans() }}</span>
        </li>
    @endforeach
    </ul>
    {{ $this->activities->links() }}
</div>
