<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        $sortField = $request->input('sortField', 'id');
        $sortDirection = $request->input('sortDirection', 'asc');

        $query->orderBy($sortField, $sortDirection);

        $searchTerm = $request->input('search');
        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                if (is_numeric($searchTerm)) {
                    $query->orWhere('id', $searchTerm);
                }
                $query->orWhere('name', 'ilike', "%$searchTerm%")
                    ->orWhere('username', 'ilike', "%$searchTerm%")
                    ->orWhere('email', 'ilike', "%$searchTerm%");
            });
        }

        $users = $query->paginate(10);

        return view('pages.admin.users', [
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
        return view('pages.createUser');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:250',
            'username' => 'required|string|min:5|max:30|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->has('is_admin') ? 'Admin' : 'User'
        ]);

        return redirect()->route('users');
    }

    /**
     * Display user's profile
     */
    public function show(User $user)
    {
        return view('pages.profile', ['user' => $user]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('selfOrAdmin', $user);
        return view('pages.editUser', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('selfOrAdmin', $user);

        if ($request->name !== $user->name) $request->validate([
            'name' => 'nullable|string|max:250',
        ]);
        if ($request->username !== $user->username) $request->validate([
            'username' => 'required|string|min:5|max:30|unique:users'
        ]);
        if ($request->email !== $user->email) $request->validate([
            'email' => 'required|email|max:250|unique:users'
        ]);

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');

        if ($request->input('password') != null) {
            $request->validate(['password' => 'required|min:8|confirmed']);
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return $request->adminPage ? redirect()->route('users')->with('success', 'Profile edited successfully!') : redirect()->back()->with('success', 'User edited successfully!');
    }

    /**
     * Promote the specified user.
     */
    public function promote(User $user)
    {
        if ($user->type == 'User')
            $user->type = 'Admin';
        else if ($user->type == 'Banned')
            $user->type = 'User';
        $user->save();
        return redirect()->back();
    }

    /**
     * Demote the specified user.
     */
    public function demote(Int $user_id)
    {
        $user = User::find($user_id);
        if ($user->type == 'Admin')
            $user->type = 'User';
        else if ($user->type == 'User')
            $user->type = 'Banned';
        $user->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('selfOrAdmin', $user);
        $user->delete();
        return redirect()->back();
    }
}
