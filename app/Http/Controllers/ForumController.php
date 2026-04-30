<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Models\ForumCategory;
use App\Models\Post;
use App\Models\Thread;
use App\Services\ForumService;
use Illuminate\Http\Request;


class ForumController extends Controller
{
        public function show(Forum $forum) {

        return view('forums.show', [
            'forum' => $forum,

        ]);
    }

    public function index(ForumService $service) {

        return view('home', $service->index());
    }
}
