<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailModel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MailController extends Controller
{
    function show() 
    {
        return view('auth.recovery');
    }

    function send(Request $request) 
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email);

        if (!$user->exists()) {
            return redirect()->back()->with('error', 'Email not found!');
        }

        $user = $user->first();
        $random_password = Str::random(8);

        $user->password = Hash::make($random_password);
        $user->save();

        $mailData = [
            'name' => $user->name,
            'password' => $random_password,
            'email' => $request->email,
        ];
        
        Mail::to($request->email)->send(new MailModel($mailData));
        return redirect()->route('login')->with('success', 'Email successfully sent!');
    }
}
