<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function show(User $user) {
        return view('users.show', compact('user'));
    }



}
