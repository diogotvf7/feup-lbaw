<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\ContentVersion;
use App\Events\AnswerEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
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
        if (!Auth::check()) {
            return redirect('/login');
        } else {
            $request->validate([
                'body' => 'required|string|max:250'
            ]);
            
            $user = Auth::user();
            
            $answer = new Answer();
            $answer->author = $user->id;
            $answer->question_id = $request->question_id;

            $this->authorize('create', $answer);

            $answer->save();

            $contentversion = new ContentVersion();
            $contentversion->body = $request->body;
            $contentversion->type = 'ANSWER';
            $contentversion->answer_id = $answer->id;
            $contentversion->save();

            //Send notification to author about receiving an answer
            $this->answerEvent($user->id, $request->question_id);
    
            return redirect()->back()->with('success', 'Answer added successfully!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:250'
        ]);
        
        $answer = Answer::findOrFail($request->answer_id);
        $this->authorize('update', $answer);

        $contentversion = new ContentVersion();
        $contentversion->body = $request->body;
        $contentversion->type = 'ANSWER';
        $contentversion->answer_id = $request->answer_id;
        $contentversion->save();

        return redirect()->back()->with('success', 'Answer edited successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $answer = Answer::findOrFail($request->answer_id);
        $this->authorize('delete', $answer);
        $answer->delete();
        return redirect()->back()->with('success', 'Answer removed successfully!');
    }

    public function answerEvent(Request $request)
    {
        
        event(new answerEvent(91, 1));
        return "evented";
    }
}
