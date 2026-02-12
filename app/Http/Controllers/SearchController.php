<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $query = $request->query('query');

        $threads = Thread::where('title', 'LIKE', "%{$query}%")
            ->with('user', 'forum')
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('search.results', ['threads' => $threads, 'query' => $query]);
    }
}
