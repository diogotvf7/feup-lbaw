<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     // Get the sorting parameters from the request
    //     $sortField = $request->input('sortField', 'id'); // Default to 'id' if not provided
    //     $sortDirection = $request->input('sortDirection', 'asc'); // Default to 'asc' if not provided

    //     // Query the database using Eloquent and paginate the results
    //     $users = User::orderBy($sortField, $sortDirection)->paginate(10);

    //     // Pass the sorted and paginated users to the view
    //     return view('pages.admin', compact('users', 'sortField', 'sortDirection'));
    // }

    public function index(Request $request)
    {
        $query = User::query();

        $sortField = $request->input('sortField', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        $query->orderBy($sortField, $sortDirection);

        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")
                    ->orWhere('username', 'like', "%$searchTerm%")
                    ->orWhere('email', 'like', "%$searchTerm%");
            });
        }

        $users = $query->paginate(10);

        return view('pages.admin', [
            'users' => $users,
            'sortField' => $sortField,
            'sortDirection' => $sortDirection,
            'searchTerm' => $searchTerm,
        ]);
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
        $user = new User();

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = $request->password('password');

        $user->member_since = now();
        $user->experience = 1;
        $user->score = 0;
        $user->is_banned = false;
        $user->is_admin = false;

        $user->save();
        return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Int $user_id)
    {
        $user = User::find($user_id);
        return view('pages.edit_user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Int $user_id)
    {
        $user = User::find($user_id);

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->is_admin = $request->filled('is_admin');
        $user->is_banned = $request->filled('is_banned');

        if ($request->input('password') != null) {
            $user->password = $request->password('password');
        }
       
        $user->save();
        return redirect()->route('users');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Int $user_id)
    {
        $user = User::find($user_id);
        $user->delete();
        return redirect()->route('users');
    }
}
