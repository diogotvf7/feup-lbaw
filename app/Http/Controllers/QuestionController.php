<?php

namespace App\Http\Controllers;

use App\Events\UpvoteEvent;
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
    public function index(Request $request)
    {
        $path = $request->path();
        $path_segments = explode('/', trim($path, '/'));
        $title = '';

        if (isset($path_segments[1])) {
            if ($path_segments[1] == 'followed') {
                $title = '<h1>Followed Question</h1>';
            } else if ($path_segments[1] == 'top') {
                $title = '<h1>Top Questions</h1>';
            } else if ($path_segments[1] == 'tag') {
                $tag = Tag::findOrFail($path_segments[2]);
                $user = (Auth::check()) ? User::find(Auth::user()->id) : null;
                if (!$tag->approved)
                    return redirect()->intended('questions');
                $title = '
                <div>
                    <h1 class="d-flex flex-wrap gap-3">
                        Questions Tagged 
                        <span class="badge bg-primary">' . $tag->name . '</span>' .
                        ((!$user) 
                            ? '<button id="follow-tag" class="btn btn-primary" data-id="'. $tag->id . '" data-status="noAuth">
                                    <i class="bi bi-bookmark"></i>
                                </button>' 
                            : (($user->followsTag($tag->id)) 
                                ? '<button id="follow-tag" class="btn btn-primary" data-id="'. $tag->id . '" data-status="follows">
                                        <i class="bi bi-bookmark-fill"></i>
                                    </button>'
                                : '<button id="follow-tag" class="btn btn-primary" data-id="'. $tag->id . '" data-status="!follows">
                                        <i class="bi bi-bookmark"></i>
                                    </button>')) .
                    '</h1>
                    <p>'
                    . $tag->description .
                    '</p>
                </div>
                ';
            }
        } else {
            $title = '<h1>All Questions</h1>';
        }
        return view('pages.questions', ['title' => $title]);
    }

    public function fetch(Request $request)
    {
        $path = $request->path();
        $path_segments = explode('/', trim($path, '/'));

        $query = Question::query()
            ->select('questions.*')
            ->leftJoin('content_versions', function ($join) {
                $join->on('content_versions.question_id', '=', 'questions.id')
                    ->where('content_versions.id', '=', function ($sub_query) {
                        $sub_query->select('id')
                            ->from('content_versions')
                            ->whereColumn('question_id', 'questions.id')
                            ->orderByDesc('date')
                            ->limit(1);
                    });
            });

        if (isset($path_segments[2])) {
            if ($path_segments[2] == 'followed') {
                $query->join('followed_questions', 'questions.id', '=', 'followed_questions.question_id')
                    ->where('user_id', $request->user()->id);
            } else if ($path_segments[2] == 'top') {
                $query->withCount('upvotes', 'downvotes')
                    ->orderBy('upvotes_count', 'desc')
                    ->orderBy('downvotes_count');
            } else if ($path_segments[2] == 'tag') {
                $query->whereHas('tags', function ($sub_query) use ($path_segments) {
                    $sub_query->where('tags.id', $path_segments[3]);
                });
            }
        }
        if ($request->has('no-answers')) {
            $query->whereDoesntHave('answers');
        } 
        if ($request->has('no-accepted-answers')) {
            $query->whereNull('questions.correct_answer');
        }
        if ($request->has('tags') && $request['tags']) {
            $tags = json_decode($request->tags);
            
            $query->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhereHas('tags', function ($sub_query) use ($tag) {
                        $sub_query->where('tags.id', $tag->value);
                    });
                }
            });
        }
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('content_versions.date');
                    break;
                case 'votes':
                    $query->withCount('upvotes', 'downvotes')
                        ->orderBy('upvotes_count', 'desc')
                        ->orderBy('downvotes_count');
                    break;
                case 'newest':
                    $query->orderBy('content_versions.date', 'desc');
                    break;
                case 'answers':
                    $query->withCount('answers')
                        ->orderBy('answers_count', 'desc');
                default:
                    $query->orderBy('content_versions.date', 'desc');
                    break;
            }
        } else {
            $query->orderBy('content_versions.date', 'desc');
        }

        $questions = $query->paginate(10);

        foreach ($questions as $question) {
            $question->user;
            $question->voteBalance();
            $question->tags;
            $question->answers;
            $question->updatedVersion;
            $question->firstVersion;
            $question->created = \Carbon\Carbon::parse($question->firstVersion->date)->diffForHumans();
            $question->updated = \Carbon\Carbon::parse($question->updatedVersion->date)->diffForHumans();
        }

        $response = [
            'authenticated' => Auth::check(),
            'questions' => $questions,
        ];

        return $response;
    }

    /**
     * Fetch the tags of a question.
     */
    public function fetchTags(Question $question)
    {
        $tags = $question->tags;
        return $tags;
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

        $tags = json_decode($request->tags);

        if ($tags) {
            foreach ($tags as $tag) {
                $question->tags()->attach($tag->value);
            }
        }

        return redirect('/questions/' . $question->id)->with('question-create', ['Question created successfully!']);
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
     * Display the preview for the specified resource.
     */
    public function preview(Question $question)
    {
        return view('partials.questionPreview', ['question' => $question])->render();
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

        $question->tags()->detach();
        $tags = json_decode($request->tags);
        if ($tags) {
            foreach ($tags as $tag) {
                $question->tags()->attach($tag->value);
            }
        }

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

    public function upvote(Question $question)
    {
        $this->authorize('vote', $question);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->upvoted('question', $question->id)) {
            Vote::where([
                ['user_id', $user->id],
                ['question_id', $question->id],
            ])->delete();
        } else {
            if ($user->downvoted('question', $question->id))
                Vote::where([
                    ['user_id', $user->id],
                    ['question_id', $question->id],
                ])->delete();
            $vote_id = Vote::create([
                'is_upvote' => true,
                'type' => 'QUESTION',
                'user_id' => $user->id,
                'question_id' => $question->id,
            ])->id;

            $this->upvoteEvent(Auth::user()->id, $vote_id);
        }
        return ['voteBalance' => $question->voteBalance()];
    }

    public function downvote(Question $question)
    {
        $this->authorize('vote', $question);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->downvoted('question', $question->id)) {
            Vote::where([
                ['user_id', $user->id],
                ['question_id', $question->id],
            ])->delete();
        } else {
            if ($user->upvoted('question', $question->id))
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

    public function follow(Question $question)
    {
        $this->authorize('vote', $question);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->followsQuestion($question->id)) {
            $user->followedQuestions()->detach($question->id);
            return "Unfollowed";
        } else {
            $user->followedQuestions()->attach($question->id);
            return "Followed";
        }
    }

    public function upvoteEvent($user_id, $vote_id)
    {
        event(new UpvoteEvent($user_id, $vote_id));
    }
}
