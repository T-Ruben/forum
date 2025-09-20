<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'content' => ['required', 'min:2', 'max:4000'],
            'thread_id' => 'required|exists:threads,id',
        ]);

        $validated['user_id'] = 1;

        Post::create($validated);

        return redirect()->back();
    }
}
