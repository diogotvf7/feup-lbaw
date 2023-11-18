@extends('layouts.app')

@section('content')
    <div id="adminTabs" class="tab-content">
        <div class="tab-pane fade active show" id="users" role="tabpanel">
            <table>
                <thead>
                    <tr>
                        <th></th> <!-- Column for checkbox -->
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Experience</th>
                        <th>Score</th>
                        <th>Member Since</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->experience }}</td>
                            <td>{{ $user->score }}</td>
                            <td>{{ $user->member_since }}</td>
                            <td>
                                {{ $user->isAdmin ? 'Admin' : ($user->isBanned ? 'Banned' : 'User') }}
                            </td>
                            <td>
                              <!-- Sugestao do GPT -->
                              <!-- <a href="{{ route('users.show', $user->id) }}">View</a>
                              <a href="{{ route('users.edit', $user->id) }}">Edit</a>
                              <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit">Delete</button>
                              </form> --> 
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="topics" role="tabpanel">
            Second part of the page
        </div>
    </div>
@endsection
