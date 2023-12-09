<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::check()) {
            return redirect('/login');
        } else {
            $request->validate([
                'body' => 'required|string|min:20|max:30000'
            ]);
            
            $user = Auth::user();
            
            $comment = new Comment();
            $comment->body = $request->body;
            $comment->type = 'ANSWER';
            $comment->author = $user->id;
            $comment->answer_id = $request->answer_id;

            //$this->authorize('create', $comment);

            $comment->save();
    
            return redirect()->back()->with('success', 'Comment added successfully!');
        }
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
