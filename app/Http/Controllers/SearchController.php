<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $query = trim($request->query('query') ?? '');
        $threadOnly = $request->boolean('threadOnly');

        if ($threadOnly) {
            $threads = Thread::whereFullText('title', $query)
                ->with('user', 'forum')
                ->get();

            $results = collect();

            foreach ($threads as $thread) {
                $results->push([
                    'type' => 'thread',
                    'model' => $thread,
                    'created_at' => $thread->created_at,
                ]);
            }

            $results = $results->sortByDesc('created_at')->values();

            $page = request()->get('page', 1);
            $perPage = 25;

            $paginated = new LengthAwarePaginator(
                $results->forPage($page, $perPage),
                $results->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );

            return view('search.results', [
                'results' => $paginated,
                'query' => $query,
            ]);
        }

        $threads = Thread::whereFullText('title' ,$query)
            ->with(['user', 'forum'])
            ->get();

        $posts = Post::whereFullText('content', $query)
            ->with(['user', 'parent' , 'thread', 'profileOwner', 'replies'])
            ->get();

        $results = collect();

        foreach ($threads as $thread) {
            $results->push([
                'type' => 'thread',
                'model' => $thread,
                'created_at' => $thread->created_at,
            ]);
        }

        foreach ($posts as $post) {

        if ($post->thread) {
            $type = 'post_thread';
        } elseif ($post->profileOwner && $post->parent_id === null) {
            $type = 'post_profile';
        } else {
            $type = 'post_reply';
        }

        $results->push([
            'type' => $type,
            'model' => $post,
            'created_at' => $post->created_at,
        ]);
        }

        $results = $results
            ->whereNull('deleted_at')
            ->sortByDesc('created_at')
            ->values();

        $page = request()->get('page', 1);
        $perPage = 25;

        $paginated = new LengthAwarePaginator(
            $results->forPage($page, $perPage),
            $results->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('search.results', [
            'results' => $paginated,
            'query' => $query,
        ]);
    }
}
