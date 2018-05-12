@extends('layouts.app')
@section('title')
    Edit "{{$item->title}}"
@endsection

@section('content')
    <h1>@yield('title')</h1>
    
    <div>
        {!! Form::open(['action' => ['FoldersController@update', 'id' => $item->id]]) !!}
            <div class="form-group">
                {{ Form::label('title', 'Title') }}
                {{ Form::text('title', $item->title, ['class' => 'form-control']) }}
            </div>
            {{ Form::hidden('_method', 'PUT') }}
            
            {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
            
            <a href="{{ route('folders.index') }}" class="btn btn-light">Cancel</a>
        {!! Form::close() !!}
    </div>
@endsection