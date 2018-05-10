@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    
    <div>
        {!! Form::open(['action' => ['PostsController@update', 'id' => $post->id]]) !!}
            <div class="form-group">
                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', $post->title, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                    {{ Form::label('body', 'Body') }}
                    {{ Form::textarea('body', $post->body, ['class' => 'form-control']) }}
            </div>
            {{ Form::hidden('_method', 'PUT') }}
            {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
        {!! Form::close() !!}
    </div>
@endsection