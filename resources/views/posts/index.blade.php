@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    @auth
    <p><a href="{{ route('posts.create') }}" class="btn btn-success">New post</a></p>
    @endauth
    
    @if(count($items) > 0)
        <div>
        @foreach($items as $post)
            <div class="card mb-4">
                <div class="card-body">
                    <h2><a href="{{ route('posts.show', ['id' => $post->id]) }}">{{ $post->title }}</a></h2>
                    <div>
                        {{ $post->body }}
                    </div>
                    <div><small class="text-muted">Posted by {{ $post->user->name }}</small></div>
                    <div><small class="text-muted">Folder: {{ $post->folder->title }}</small></div>
                </div>
                
                @if(!Auth::guest() && Auth::user()->id == $post->user->id)
                <div class="card-footer">
                    <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="btn btn-primary">Edit</a>

                    {!! Form::open(['action' => ['PostsController@destroy', 'id' => $post->id], 'class' => 'd-inline-block']) !!}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('Trash', ['class' => 'btn btn-danger']) }}
                    {!! Form::close() !!}
                </div>
                @endif
            </div>
        @endforeach
        </div>
    @else
        <p>No posts found, <a href="{{ route('posts.create') }}">create a new one</a></p>
    @endif

    @auth
        @if(count($trashed) > 0)
            <h2>Trash</h2>

            <table class="table table-striped">
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            @foreach($trashed as $post)
                <tr>
                    <td>
                        <a href="{{ route('posts.show', ['id' => $post->id]) }}">{{ $post->title }}</a>
                    </td>
                    <td>
                        {!! Form::open(['action' => ['PostsController@restore', 'id' => $post->id], 'class' => 'd-inline-block']) !!}
                            {{ Form::submit('Restore', ['class' => 'btn btn-success']) }}
                        {!! Form::close() !!}
                        
                        {!! Form::open(['action' => ['PostsController@force_delete', 'id' => $post->id], 'class' => 'd-inline-block']) !!}
                            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </table>
        @endif
    @endauth
@endsection