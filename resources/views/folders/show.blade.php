@extends('layouts.app')
@section('title', $item->title)

@section('content')
    <h1>@yield('title')</h1>
    
    <div>
        {{ $item->body }}
    </div>
@endsection