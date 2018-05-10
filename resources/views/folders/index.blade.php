@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>

    @auth
    <p><a href="{{ route('folders.create') }}" class="btn btn-success">New folder</a></p>
    @endauth
    
    @if(count($items) > 0)
        <div>
        @foreach($items as $folder)
            <div class="card mb-4">
                <div class="card-body">
                    <a href="{{ route('folders.show', ['id' => $folder->id]) }}">{{ $folder->title }}</a>
                </div>
                
                @if(!Auth::guest() && Auth::user()->id == $folder->user->id)
                <div class="card-footer">
                    <a href="{{ route('folders.edit', ['id' => $folder->id]) }}" class="btn btn-primary">Edit</a>

                    {!! Form::open(['action' => ['FoldersController@destroy', 'id' => $folder->id], 'class' => 'd-inline-block']) !!}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::submit('Trash', ['class' => 'btn btn-danger']) }}
                    {!! Form::close() !!}
                </div>
                @endif
            </div>
        @endforeach
        </div>
    @else
        <p>No folders found, <a href="{{ route('folders.create') }}">create a new one</a></p>
    @endif

    @if(count($trashed) > 0)
        <h2>Trash</h2>

        <table class="table table-striped">
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        @foreach($trashed as $folder)
            <tr>
                <td>
                    <a href="{{ route('folders.show', ['id' => $folder->id]) }}">{{ $folder->title }}</a>
                </td>
                <td>
                    {!! Form::open(['action' => ['FoldersController@restore', 'id' => $folder->id], 'class' => 'd-inline-block']) !!}
                        {{ Form::submit('Restore', ['class' => 'btn btn-success']) }}
                    {!! Form::close() !!}
                    
                    {!! Form::open(['action' => ['FoldersController@force_delete', 'id' => $folder->id], 'class' => 'd-inline-block']) !!}
                        {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </table>
    @endif
@endsection