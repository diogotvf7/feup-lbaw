<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class BlockedController extends Controller
{
    public function show(){
        Auth::logout();
        return view('auth.banned');
    }
}
