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
        $questions = Question::select('questions.*')->whereRaw("search @@ to_tsquery(replace(?, ' ', '<->')) OR search @@ to_tsquery(replace(?, ' ', '|'))", [$searchTerm, $searchTerm])->orderByRaw("title ILIKE ? DESC, ts_rank(search, to_tsquery(replace(?, ' ', '<->'))) DESC, ts_rank(search, to_tsquery(replace(?, ' ', '|'))) DESC", [$likeSearchTerm, $searchTerm, $searchTerm])->get();
        $tags = Tag::select('tags.*')->whereRaw("search @@ to_tsquery(replace(?, ' ', '<->')) OR search @@ to_tsquery(replace(?, ' ', '|'))", [$searchTerm, $searchTerm])->orderByRaw("name ILIKE ? DESC, ts_rank(search, to_tsquery(replace(?, ' ', '<->'))) DESC, ts_rank(search, to_tsquery(replace(?, ' ', '|'))) DESC", [$likeSearchTerm, $searchTerm, $searchTerm])->get();
        if ($request->ajax()) {
            return view('pages.search', ['includeAll' => False, 'questions' => $questions, 'tags' => $tags, 'query' => $request->searchTerm])->render();
        } else return view('pages.search', ['includeAll' => True, 'questions' => $questions, 'tags' => $tags, 'query' => $request->searchTerm]);
    }
}
