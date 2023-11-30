<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Vote;
use App\Models\User;
use App\Models\Question;
use App\Models\ContentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $path = $request->path();
        $path_segments = explode('/', trim($path, '/'));

        $query = Question::query();
        if (isset($path_segments[2])) {
            if ($path_segments[2] == 'followed') 
            {
                $query->join('followed_questions', 'questions.id', '=', 'followed_questions.question_id')
                    ->where('user_id', $request->user()->id);
            }
            else if ($path_segments[2] == 'top') 
            {
                $query->withCount('upvotes', 'downvotes')->orderBy('upvotes_count', 'desc')->orderBy('downvotes_count');
            }
            else if ($path_segments[2] == 'tag')
            {
                $query->whereHas('tags', function ($sub_query) use ($path_segments) {
                    $sub_query->where('tags.id', $path_segments[3]);
                })->get();
            }
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
            'title' => 'required|string|min:5|max:150',
            'body' => 'required|string|min:20|max:30000'
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

        return redirect()->route('questions')->with('question-create', ['Question created successfully!', '/questions/' . $question->id]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        if (!Auth::check()) 
            return view('pages.question', ['question' => $question, 'vote' => '', 'follow' => '']);
        $currentUser = User::find(Auth::user()->id);
        $vote = $currentUser->voted('question', $question->id);
        $follow = $currentUser->followsQuestion($question->id);
        return view('pages.question', ['question' => $question, 'vote' => $vote, 'follow' => $follow]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $request->validate([
            'body' => 'required|string|min:20|max:30000'
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
        return redirect()->intended('questions')->with('success', 'Question removed successfully!');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->searchTerm ? ($request->searchTerm . ':*') : '*';
        $likeSearchTerm = '*' . $request->searchTerm . '*';
        $questions = Question::select('questions.*')->join('content_versions', 'content_versions.question_id', '=', 'questions.id')->whereRaw("(search_title || search_body) @@ to_tsquery(replace(?, ' ', '<->')) OR (search_title || search_body) @@ to_tsquery(replace(?, ' ', '|'))", [$searchTerm, $searchTerm])->orderByRaw("questions.title ILIKE ? DESC, ts_rank(search_title || search_body, to_tsquery(replace(?, ' ', '<->'))) DESC, ts_rank(search_title || search_body, to_tsquery(replace(?, ' ', '|'))) DESC", [$likeSearchTerm, $searchTerm, $searchTerm])->get();
        
        if($request->ajax()){
            return view('pages.search', ['includeAll' => False, 'questions' => $questions, 'query' => $request->searchTerm])->render();
        }   
        else return view('pages.search', ['includeAll' => True, 'questions' => $questions, 'query' => $request->searchTerm]);
    }

    public function upvote(Question $question)
    {        
        $this->authorize('vote', $question);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->upvoted($question->id)) {
            Vote::where([
                ['user_id', $user->id],
                ['question_id', $question->id],
                ])->delete();
        } else {
            if ($user->downvoted($question->id))
                Vote::where([
                    ['user_id', $user->id],
                    ['question_id', $question->id],
                    ])->delete();
            Vote::create([
                'is_upvote' => true,
                'type' => 'QUESTION',
                'user_id' => $user->id,
                'question_id' => $question->id,
            ]);
        }
        return ['voteBalance' => $question->voteBalance()];
    }

    public function downvote(Question $question)
    {
        $this->authorize('vote', $question);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->downvoted($question->id)) {
            Vote::where([
                ['user_id', $user->id],
                ['question_id', $question->id],
                ])->delete();
        } else {
            if ($user->upvoted($question->id))
                Vote::where([
                    ['user_id', $user->id],
                    ['question_id', $question->id],
                    ])->delete();
            Vote::create([
                'is_upvote' => false,
                'type' => 'QUESTION',
                'user_id' => $user->id,
                'question_id' => $question->id,
            ]);
        }    
        return ['voteBalance' => $question->voteBalance()];
    }
}
