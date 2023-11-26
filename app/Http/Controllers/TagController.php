<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index() 
    {
        return view('pages.tags');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = Tag::query();

        $sortField = $request->input('sortField', 'approved');
        $sortDirection = $request->input('sortDirection', 'asc');

        if ($sortField == 'questions' || $sortField == 'usersThatFollow')
            $query->withCount($sortField)->orderBy(Str::snake($sortField) . '_count', $sortDirection);
        else
            $query->orderBy($sortField, $sortDirection);
        
        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                if (is_numeric($searchTerm)) {
                    $query->orWhere('id', $searchTerm);
                }
                $query->orWhere('name', 'ilike', "%$searchTerm%")
                    ->orWhere('description', 'ilike', "%$searchTerm%");
            });
        }

        $tags = $query->paginate(10);

        return view('pages.admin.tags', [
            'tags' => $tags,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'searchTerm' => $searchTerm,
        ]);
    }

    public function fetch(Request $request)
    {
        $tags = Tag::where('approved', TRUE)->paginate(30);
        foreach ($tags as $tag) {
            $tag->questions;
            $tag->usersThatFollow;
        }
        return $tags;
    }


    public function approve(Tag $tag) 
    {
        $tag->approved = true;
        $tag->save();
        return redirect()->back();
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
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('pages.editTag', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->back();
    }
}
