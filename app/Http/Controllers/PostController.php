<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
    public function store(Request $request) {
        $content = $request->input('content');

        $plain = trim(strip_tags($content));

        if(strlen($plain) < 1) {
            return back()
                ->withErrors(['content' => 'Must have at least one character.']);
        }
        if(strlen($plain) > 5000) {
            return back()
                ->withErrors(['content' => 'Must have less than 5000 characters.']);
        }



        $validated = $request->validate([
            'content' => ['required', 'string'],
            'thread_id' => 'required|exists:threads,id',
        ]);

        try {
            $validated['content'] = Purifier::clean(trim($validated['content']), 'quill');
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
