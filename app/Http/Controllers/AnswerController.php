<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\Vote;
use App\Models\Answer;
use App\Models\ContentVersion;
use App\Events\AnswerEvent;
use App\Events\UpvoteEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $question = Question::findOrFail($request->question_id);
        $answers = $question->answers;

        $correctAnswer = $question->correctAnswer;

        switch ($request->sort) {
            case 'oldest':
                $answers = $answers->sortBy('created_at');
                break;
            case 'votes':
                $answers = $answers->sortByDesc('vote_balance');
                break;
            case 'newest':
                $answers = $answers->sortByDesc('updated_at');
                break;
            default:
                $answers = $answers->sortByDesc('created_at');
                break;
        }

        if ($correctAnswer) {
            $vote = $request->user() ? $request->user()->voted('answer', $correctAnswer->id) : null;
            $answersViews = [view('partials.answer', ['answer' => $correctAnswer, 'vote' => $vote])->render()];
        }
        else $answersViews = [];
        
        foreach ($answers as $answer) {
            if ($answer->id == $question->correct_answer) {
                continue;
            }
            $vote = $request->user() ? $request->user()->voted('answer', $answer->id) : null;
            $answersViews[] = view('partials.answer', ['answer' => $answer, 'vote' => $vote])->render();
        }
        return response()->json(['answers' => $answersViews]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        return view('partials.answer');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $request->validate([
            'body' => 'required|string|min:20|max:30000'
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

    public function upvote(Answer $answer)
    {
        $this->authorize('vote', $answer);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->upvoted('answer', $answer->id)) {
            Vote::where([
                ['user_id', $user->id],
                ['answer_id', $answer->id],
            ])->delete();
        } else {
            if ($user->downvoted('answer', $answer->id))
                Vote::where([
                    ['user_id', $user->id],
                    ['answer_id', $answer->id],
                ])->delete();
            $vote_id = Vote::create([
                'is_upvote' => true,
                'type' => 'ANSWER',
                'user_id' => $user->id,
                'answer_id' => $answer->id,
            ])->id;
            $this->upvoteEvent(Auth::user()->id, $vote_id);
        }
        return ['voteBalance' => $answer->getVoteBalanceAttribute()];
    }

    public function downvote(Answer $answer)
    {
        $this->authorize('vote', $answer);
        $user = User::findOrFail(Auth::user()->id);

        if ($user->downvoted('answer', $answer->id)) {
            Vote::where([
                ['user_id', $user->id],
                ['answer_id', $answer->id],
            ])->delete();
        } else {
            if ($user->upvoted('answer', $answer->id))
                Vote::where([
                    ['user_id', $user->id],
                    ['answer_id', $answer->id],
                ])->delete();
            Vote::create([
                'is_upvote' => false,
                'type' => 'ANSWER',
                'user_id' => $user->id,
                'answer_id' => $answer->id,
            ])->id;
        }
        return ['voteBalance' => $answer->getVoteBalanceAttribute()];
    }

    public function correct(Request $request)
    {
        $question = Question::findOrFail($request->question_id);
        $answer = Answer::findOrFail($request->answer_id);

        $this->authorize('update', $answer);

        if ($question->correct_answer == $answer->id)
            $question->correct_answer = null;
        else
            $question->correct_answer = $answer->id;
        
        $question->save();

        return redirect()->back()->with('success', 'Correct answer edited successfully!');
    }

    public function upvoteEvent($user_id, $vote_id)
    {
        event(new UpvoteEvent($user_id, $vote_id));
    }

    public function answerEvent($user_id, $question_id)
    {

        event(new answerEvent($user_id, $question_id));
    }
}
