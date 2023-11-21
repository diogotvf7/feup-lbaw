@extends('layouts.app')

@section('content')    
    <div class="d-flex flex-column">
        <form class="d-flex m-2 gap-2">
            <input class="form-control" type="search" name="search" placeholder="Search" value="{{ $searchTerm }}">
            <button class="btn btn-secondary text-nowrap" type="submit">Search user</button>
            <a class="btn btn-secondary text-nowrap" href="{{ route('user.create') }}">Create user</a>
        </form>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=id&sortDirection={{ ($sortField=='id' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Id &NonBreakingSpace;
                            @if ($sortField == 'id')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=name&sortDirection={{ ($sortField=='name' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Name &NonBreakingSpace;
                            @if ($sortField == 'name')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=username&sortDirection={{ ($sortField=='username' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Username &NonBreakingSpace;
                            @if ($sortField == 'username')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=email&sortDirection={{ ($sortField=='email' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Email &NonBreakingSpace;
                            @if ($sortField == 'email')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=experience&sortDirection={{ ($sortField=='experience' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Experience &NonBreakingSpace;
                            @if ($sortField == 'experience')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=score&sortDirection={{ ($sortField=='score' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Score &NonBreakingSpace;
                            @if ($sortField == 'score')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=member_since&sortDirection={{ ($sortField=='member_since' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Member Since &NonBreakingSpace; 
                            @if ($sortField == 'member_since')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/users?sortField=type&sortDirection={{ ($sortField=='type' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Type &NonBreakingSpace;
                            @if ($sortField == 'type')
                                @if ($sortDirection == 'asc')
                                    <i class="bi bi-caret-down-fill"></i>
                                @else
                                    <i class="bi bi-caret-up-fill"></i>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="table-active">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->experience }}</td>
                        <td>{{ $user->score }}</td>
                        <td>{{ $user->member_since }}</td>
                        <td>{{ $user->type }}</td>
                        <td class="d-flex flex-wrap gap-1">
                            <form class="d-inline-block" action="{{ route('users.destroy', $user->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')" aria-label="Remove User">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form> 
                            <form class="d-inline-block" action="{{ route('users.edit', $user->id) }}" method="GET">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary btn-sm" aria-label="Edit User">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </form>
                            @if ($user->type !== 'Admin') 
                                <form class="d-inline-block" action="{{ route('user.promote', $user->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <button type="submit" class="btn btn-success btn-sm" aria-label="Promote User" data-toggle="tooltip" title="Promote {{ $user->username }}" aria-label="Promote {{ $user->username }}" onclick="return confirm('Are you sure you want to promote this user?')" >
                                        <i class="bi bi-caret-up-fill"></i>
                                    </button>
                                </form>
                            @endif
                            @if ($user->type !== 'Banned') 
                                <form class="d-inline-block" action="{{ route('user.demote', $user->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <button type="submit" class="btn btn-danger btn-sm" aria-label="Demote User" data-toggle="tooltip" title="Demote {{ $user->username }}" aria-label="Demote {{ $user->username }}" onclick="return confirm('Are you sure you want to demote this user?')" >
                                        <i class="bi bi-caret-down-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $users->appends(['sortField' => $sortField, 'sortDirection' => $sortDirection, 'search' => $searchTerm])->links("pagination::bootstrap-4") }}        
        </div>
    </div>
@endsection
