@extends('layouts.app')

@section('content')    
    <div class="d-flex flex-column">
        <form class="d-flex m-2 gap-2">
            <input class="form-control" type="search" name="search" placeholder="Search" value="{{ $searchTerm }}">
            <button class="btn btn-secondary text-nowrap" type="submit">Search tag</button>
            <a class="btn btn-secondary text-nowrap" href="{{ route('tag.create') }}">Create tag</a>
        </form>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">
                        <a 
                            class="d-flex text-decoration-none text-reset" 
                            href="/admin/tags?sortField=id&sortDirection={{ ($sortField=='id' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
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
                            href="/admin/tags?sortField=name&sortDirection={{ ($sortField=='name' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
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
                            href="/admin/tags?sortField=description&sortDirection={{ ($sortField=='description' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Description &NonBreakingSpace;
                            @if ($sortField == 'description')
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
                            href="/admin/tags?sortField=questions&sortDirection={{ ($sortField=='questions' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Questions &NonBreakingSpace;
                            @if ($sortField == 'questions')
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
                            href="/admin/tags?sortField=usersThatFollow&sortDirection={{ ($sortField=='usersThatFollow' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Followers &NonBreakingSpace;
                            @if ($sortField == 'usersThatFollow')
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
                            href="/admin/tags?sortField=approved&sortDirection={{ ($sortField=='approved' && $sortDirection=='asc') ? 'desc' : 'asc' }}&search={{ $searchTerm }}">
                            Status &NonBreakingSpace;
                            @if ($sortField == 'approved')
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
                @foreach($tags as $tag)
                    <tr class="table-active">
                        <td>{{ $tag->id }}</td>
                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->description }}</td>
                        <td>{{ $tag->questions->count() }}</td>
                        <td>{{ $tag->usersThatFollow->count() }}</td>
                        <td class="{{ $tag->approved ? '' : 'text-danger' }}">{{ $tag->approved ? 'Active' : 'Pending Approval' }}</td>
                        <td class="d-flex flex-wrap gap-1">
                            <a class="btn btn-primary btn-sm" href="{{ route('tag.show', $tag->id) }}" aria-label="Browse Tag">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <form class="d-inline-block" action="{{ route('tag.destroy', $tag->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this tag?')" aria-label="Delete Tag">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form> 
                            <form class="d-inline-block" action="{{ route('tag.edit', $tag->id) }}" method="GET">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary btn-sm" aria-label="Edit Tag">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </form>
                            @if (!$tag->approved) 
                                <form class="d-inline-block" action="{{ route('tag.approve', $tag->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    {{ method_field('PATCH') }}
                                    <button type="submit" class="btn btn-success btn-sm" aria-label="Approve Tag" data-toggle="tooltip" title="Appprove {{ $tag->name }}" aria-label="Approve {{ $tag->name }}" onclick="return confirm('Are you sure you want to approve this tag?')" >
                                        <i class="bi bi-check-square-fill"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $tags->appends(['sortField' => $sortField, 'sortDirection' => $sortDirection, 'search' => $searchTerm])->links("pagination::bootstrap-4") }}        
        </div>
    </div>
@endsection
