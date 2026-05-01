<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    public function results(Request $request, SearchService $service)
    {
        $query = trim($request->query('query') ?? '');
        $threadOnly = $request->boolean('threadOnly');

        $paginated = $service->searchResults($query, $threadOnly);

        return view('search.results', [
            'results' => $paginated,
            'query' => $query,
        ]);
    }
}
