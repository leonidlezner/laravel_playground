@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    
    <div>
        {!! Form::open(['action' => 'FoldersController@store']) !!}
            <div class="form-group">
                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', '', ['class' => 'form-control']) }}
            </div>
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
            
            <a href="{{ route('folders.index') }}" class="btn btn-light">Cancel</a>
        {!! Form::close() !!}
    </div>
@endsection