<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:1', 'max:4000'],
            'thread_id' => 'required|exists:threads,id',
        ]);

        try {
            $validated['content'] = trim($validated['content']);
            Auth::user()->posts()->create(($validated));

        return back()->with('success', 'Post created successfully!');

        } catch (\Exception $e) {
            Log::error('Post creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withErrors(['content' => 'Something went wrong. Please try again later.'])
                ->withInput();
        }
    }
}
