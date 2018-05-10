<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Post;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    private function findPostOrAbort($id, $includeTrashed = false)
    {
        if($includeTrashed)
        {
            $post = Post::withTrashed()->find($id);
        }
        else
        {
            $post = Post::find($id);
        }
        

        if(!$post)
        {
            abort(404, 'Blog post not found');
        }

        return $post;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->get();

        $trashed = Post::onlyTrashed()->orderBy('id', 'desc')->get();

        return view('posts.index')->with([
            'posts' => $posts,
            'trashed' => $trashed,
            'title' => 'Posts',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with([
            'title' => 'Create a new Post'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $post = new Post();

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;

        $post->save();

        return redirect()->route('posts.index')->with([
            'success' => sprintf('Post "%s" created!', $post->title)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->findPostOrAbort($id);

        return view('posts.show')->with([
            'post' => $post,
            'title' => $post->title
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->findPostOrAbort($id);

        if($post->user_id != auth()->user()->id) {
            return redirect()->route('posts.index')->with('error', 
                sprintf('You can not edit the post "%s"!', $post->title));
        }

        return view('posts.edit')->with([
            'post' => $post,
            'title' => 'Edit ' . $post->title
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        $post = $this->findPostOrAbort($id);

        $post->title = $request->input('title');
        $post->body = $request->input('body');

        $post->save();

        return redirect()->route('posts.index')->with([
            'success' => sprintf('Post "%s" updated!', $post->title)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->findPostOrAbort($id);

        if($post->user_id != auth()->user()->id) {
            return redirect()->route('posts.index')->with('error', 
                sprintf('You can not delete the post "%s"!', $post->title));
        }

        $title = $post->title;

        $post->delete();

        return redirect()->route('posts.index')->with([
            'success' => sprintf('Post "%s" deleted!', $title)
        ]);
    }


    /**
     * Restores the specified resource from trash.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $post = $this->findPostOrAbort($id, true);

        $post->restore();

        return redirect()->route('posts.index')->with([
            'success' => sprintf('Post "%s" restored!', $post->title)
        ]);
    }

    /**
     * Forces the deletion of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function force_delete($id)
    {
        $post = $this->findPostOrAbort($id, true);
        
        $title = $post->title;

        $post->forceDelete();

        return redirect()->route('posts.index')->with([
            'success' => sprintf('Post "%s" removed!', $title)
        ]);
    }
}
