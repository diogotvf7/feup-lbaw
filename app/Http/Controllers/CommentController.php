<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function fetch(Request $request)
    {
        $answer = Answer::findOrFail($request->id);
        $comments = $answer->comments;
        $commentsViews = [];
        // $currentUser = User::find(Auth::user());
        foreach ($comments as $comment) {
            $commentsViews[] = view('partials.comment', ['comment' => $comment])->render();
        }
        return response()->json(['comments' => $commentsViews]);
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
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
