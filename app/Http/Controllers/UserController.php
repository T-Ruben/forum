<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function show(User $user) {



        return view('users.show', compact('user'));
    }
}
