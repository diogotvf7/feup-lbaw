<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
    }

    public function readAllRelatedTo(Int $id)
    {
        foreach (Auth::user()->notifications as $notification) {
            if ($notification->relatedQuestionId() == $id) {
                $notification->update(['seen' => "True"]);
            }
        }
    }

    public function destroyAll()
    {
        foreach (Auth::user()->notifications as $notification) $notification->delete();
    }

    public function read()
    {
        Notification::where('user_id', Auth::user()->id)->update(['seen' => "True"]);
    }

    public function fetch()
    {
        return view('partials.notificationsCard');
    }

    public function count()
    {
        return Auth::user()->getUnreadNotificationsAttribute();
    }
}
