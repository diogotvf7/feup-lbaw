<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $searchTerm = $request->searchTerm ? ($request->searchTerm . ':*') : '*';
        $likeSearchTerm = '%' . $request->searchTerm . '%';
        $questions = Question::select('questions.*')->whereRaw("search @@ to_tsquery(replace(?, ' ', '<->')) OR search @@ to_tsquery(replace(?, ' ', '|'))", [$searchTerm, $searchTerm]);
        
        if ($request->has('no-answers')) {
            $questions->whereDoesntHave('answers');
        } 
        if ($request->has('no-accepted-answers')) {
            $questions->whereNull('questions.correct_answer');
        }
        if ($request->has('tags') && $request['tags']) {
            $tags = json_decode($request->tags);
            
            $questions->where(function ($questions) use ($tags) {
                foreach ($tags as $tag) {
                    $questions->orWhereHas('tags', function ($sub_query) use ($tag) {
                        $sub_query->where('tags.id', $tag->value);
                    });
                }
            });
        }
        // if ($request->has('sort')) {
        //     switch ($request->sort) {
        //         case 'oldest':
        //             $questions->orderBy('content_versions.date');
        //             break;
        //         case 'votes':
        //             $questions->withCount('upvotes', 'downvotes')
        //                 ->orderBy('upvotes_count', 'desc')
        //                 ->orderBy('downvotes_count');
        //             break;
        //         case 'newest':
        //             $questions->orderBy('content_versions.date', 'desc');
        //             break;
        //         case 'answers':
        //             $questions->withCount('answers')
        //                 ->orderBy('answers_count', 'desc');
        //         default:
        //             $questions->orderBy('content_versions.date', 'desc');
        //             break;
        //     }
        // } else {
        //     $questions->orderByRaw("title ILIKE ? DESC, ts_rank(search, to_tsquery(replace(?, ' ', '<->'))) DESC, ts_rank(search, to_tsquery(replace(?, ' ', '|'))) DESC", [$likeSearchTerm, $searchTerm, $searchTerm]);
        // }
               
        $tags = Tag::select('tags.*')->whereRaw("search @@ to_tsquery(replace(?, ' ', '<->')) OR search @@ to_tsquery(replace(?, ' ', '|'))", [$searchTerm, $searchTerm])->orderByRaw("name ILIKE ? DESC, ts_rank(search, to_tsquery(replace(?, ' ', '<->'))) DESC, ts_rank(search, to_tsquery(replace(?, ' ', '|'))) DESC", [$likeSearchTerm, $searchTerm, $searchTerm])->get();
        
        
        if ($request->ajax()) {
            return view('pages.search', ['includeAll' => False, 'questions' => $questions->get(), 'tags' => $tags, 'query' => $request->searchTerm])->render();
        } else return view('pages.search', ['includeAll' => True, 'questions' => $questions->get(), 'tags' => $tags, 'query' => $request->searchTerm]);
    }
}
