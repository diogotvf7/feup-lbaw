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
                'body' => 'required|string|max:30000'
            ]);
            
            $user = Auth::user();
            
            $comment = new Comment();
            $comment->body = $request->body;
            $comment->author = $user->id;

            if ($request->answer_id === NULL) {
                $comment->type = 'QUESTION';
                $comment->question_id = $request->question_id;
            }
            else {                
                $comment->type = 'ANSWER';
                $comment->answer_id = $request->answer_id;
            }

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
    public function edit(Request $request)
    {
        $request->validate([
            'body' => 'required|string|min:20|max:30000'
        ]);
        
        $comment = Comment::findOrFail($request->comment_id);
        $this->authorize('update', $comment);
        $comment->body = $request->body;
        $comment->save();

        return redirect()->back()->with('success', 'Comment edited successfully!');
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
    public function destroy(Request $request)
    {
        $comment = Comment::findOrFail($request->comment_id);
        $this->authorize('delete', $comment);
        $comment->delete();
        return redirect()->back()->with('success', 'Comment removed successfully!');
    }
}
