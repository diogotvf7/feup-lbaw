<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.questions');
    }

    public function fetch(Request $request)
    {
        $type = $request->query('filter');

        $query = Question::query();
        
        if ($type == 'followed' && Auth::check()) 
        {
            $query->join('followed_questions', 'questions.id', '=', 'followed_questions.question_id')
                ->where('user_id', $request->user()->id);
        }
        else if ($type == 'top') 
        {
            $query->withCount('upvotes', 'downvotes')->orderBy('upvotes_count', 'desc')->orderBy('downvotes_count');
        }
        // else
        // {
        //     $query->
        // }

        $questions = $query->paginate(10);

        foreach ($questions as $question) {
            $question->user;
            $question->voteBalance();
            $question->tags;
            $question->answers;
            $question->updatedVersion;
            $question->firstVersion;
            $question->timeAgo = \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans();
            }
        return $questions;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        //
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
    public function destroy(Question $question)
    {
        //
    }

    // public function top()
    // {
    //     $questions = Question::withCount('upvotes', 'downvotes')->orderBy('upvotes_count', 'desc')->orderBy('downvotes_count')->paginate(10);
    //     $sortedQuestions = $questions->getCollection()->sortByDesc(function ($question) {
    //         return $question->upvotes->count() - $question->downvotes->count();
    //     })->values();
    //     $questions->setCollection($sortedQuestions);
    //     return view('pages.topQuestions', compact('questions'));
    // }
}
