@extends('layouts.app')

@section('content')    
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <!-- <th></th> Column for checkbox -->
                    <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Experience</th>
                    <th scope="col">Score</th>
                    <th scope="col">Member Since</th>
                    <th scope="col">Status</th>
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
                        <td>
                            {{ $user->is_admin ? 'Admin' : ($user->is_banned ? 'Banned' : 'User') }}
                        </td>
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')" aria-label="Remove User">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form> 
                            <form action="{{ route('users.edit', $user->id) }}" method="GET">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary btn-sm" aria-label="Edit User">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $users->links("pagination::bootstrap-4") }}
        </div>
    </div>
@endsection
