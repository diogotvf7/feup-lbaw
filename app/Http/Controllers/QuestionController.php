<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\ContentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;

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

        $response = [
            'authenticated' => Auth::check(),
            'questions' => $questions,
        ];

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::check())
            return redirect()->route('login');
        $tags = Tag::all();
        return view('pages.createQuestion', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:5|max:100',
            'body' => 'required|string|min:20'
        ]);

        $question = Question::create([
            'title' => $request->title,
            'author' => $request->user_id
        ]);

        ContentVersion::create([
            'body' => $request->body,
            'type' => 'QUESTION',
            'question_id' => $question->id
        ]);

        return redirect()->route('questions');
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
        $request->validate([
            'body' => 'required|string|max:250'
        ]);
        
        $question = Question::findOrFail($request->question_id);
        $this->authorize('update', $question);

        $contentversion = new ContentVersion();
        $contentversion->body = $request->body;
        $contentversion->type = 'QUESTION';
        $contentversion->question_id = $request->question_id;
        $contentversion->save();

        return redirect()->back()->with('success', 'Question edited successfully!');
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
        $this->authorize('delete', $question);
        $question->delete();
        return redirect()->intended('/questions/top')->with('success', 'Question removed successfully!');
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
