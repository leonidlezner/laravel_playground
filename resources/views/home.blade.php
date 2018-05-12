@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>You are logged in!</p>

                    
                    <h2>Posts</h2>
                    <p><a href="{{ route('posts.create') }}" class="btn btn-success">New post</a></p>
                    
                    @if(count($posts) > 0)
                    <h3>My posts</h3>

                    <table class="table table-striped">
                        <tr>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    @foreach($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', ['id' => $post->id]) }}">{{ $post->title }}</a>
                            </td>
                            <td>
                                <a href="{{ route('posts.edit', ['id' => $post->id]) }}" class="btn btn-light">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
