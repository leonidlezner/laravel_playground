@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    
    <div>
        {!! Form::open(['action' => 'PostsController@store']) !!}
            <div class="form-group">
                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', '', ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                    {{ Form::label('body', 'Body') }}
                    {{ Form::textarea('body', '', ['class' => 'form-control']) }}
            </div> 
            {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
        {!! Form::close() !!}
    </div>
@endsection