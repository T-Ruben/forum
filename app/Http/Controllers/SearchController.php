<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function results(Request $request)
    {
        $input = $request->input('searchBar');

        $threads = Thread::where('title', 'LIKE', `%{$input}%`)
            ->limit(5)
            ->get();

        return view('search.results', compact($threads));
    }
}
