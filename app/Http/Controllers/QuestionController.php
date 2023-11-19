<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ContentVersion;
use Illuminate\Http\Request;

class QuestionController extends Controller
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
    public function show(Question $question)
    {
        return view('pages.question', ['question' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $contentversion = new ContentVersion();
        $contentversion->body = $request->body;
        $contentversion->type = 'QUESTION';
        $contentversion->question_id = $request->question_id;
        $contentversion->save();

        return redirect()->back()->with('success', 'Answer removed successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $question = Question::findOrFail($request->question_id);
        $question->delete();
        return redirect()->intended('/welcome');
    }

    public function top()
    {
        $questions = Question::withCount('upvotes', 'downvotes')->orderBy('upvotes_count', 'desc')->orderBy('downvotes_count')->paginate(10);
        $sortedQuestions = $questions->getCollection()->sortByDesc(function ($question) {
            return $question->upvotes->count() - $question->downvotes->count();
        })->values();
        $questions->setCollection($sortedQuestions);
        return view('pages.topQuestions', compact('questions'));
    }
}
