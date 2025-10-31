<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{

    public function personal() {
        return view('users.personal', ['user' => Auth::user()]);
    }
    public function privacy() {
        return view('users.privacy', ['user' => Auth::user()]);
    }

}
