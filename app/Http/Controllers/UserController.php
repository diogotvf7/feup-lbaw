<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
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
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:250',
            'username' => 'required|string|min:5|max:30|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('create_error_id', 'yes');
        };
  
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->has('is_admin') ? 'Admin' : 'User'
        ]);

        return redirect()->route('admin.users', ['sortField'=> 'id', 'sortDirection' => 'desc'])->with('success', ['User created successfully!', '/users/' . $user->id]);
    }

    /**
     * Display user's profile
     */
    public function show(User $user)
    {
        return view('pages.profile', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('selfOrAdmin', $user);

        $validator = Validator::make($request->all(), [
            'name' => $request->name !== $user->name ? 
                'nullable|string|max:250' : '',
            'username' => $request->username !== $user->username ? 
                'required|string|min:5|max:30|unique:users' : '',
            'email' => $request->email !== $user->email ? 
                'required|email|max:250|unique:users' : '',
            'password' => $request->password != null ?
                'required|min:8|confirmed' : ''
        ]);
 
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput()
                        ->with('edit_error_id', $request->id);
        };

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        if ($request->input('password') != null) 
            $user->password = Hash::make($request->password);    

        $user->save();
        return $request->adminPage 
            ? redirect()->back()->with('success', ['User edited successfully!', '/users/' . $user->id])
            : redirect()->back()->with('success', ['Profile edited successfully!']);
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
        return redirect()->back()->with('success', [$user->username . ' promoted successfully!']);
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
        return redirect()->back()->with('success', [$user->username . ' demoted successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $this->authorize('selfOrAdmin', $user);
        
        if($user->id === Auth::user()->id){
            Auth::logout();
            $user->delete();
            return redirect()->route('questions.top')->with('success', ['Your account was deleted successfully!']);
        }

        $user->delete();
        return redirect()->back()->with('success', [$user->username . ' deleted successfully!']);
        
    }
}
