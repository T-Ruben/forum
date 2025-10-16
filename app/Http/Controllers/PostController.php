<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:4000'],
            'thread_id' => 'required|exists:threads,id',
        ]);

        Auth::user()->posts()->create($validated);

        return redirect()->back();
    }
}
