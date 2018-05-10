@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    
    <div>
        {{ $post->body }}
    </div>
@endsection