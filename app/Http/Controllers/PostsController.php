<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\StoreBlogPost;
use \App\Folder;

class PostsController extends CrudController
{
    protected $modelName = '\App\Post';
    protected $viewFolder = 'posts';
    protected $indexRoute = 'posts.index';
    protected $resourceName = ['one' => 'Blog post', 'many' => 'Blog posts'];

    protected $validationRules = [
        'title' => 'required',
        'body' => 'required',
    ];

    public function setFolderFromRequest($item, $request)
    {
        $newFolderId = $request->input('folder_id');

        $newFolder = Folder::find($newFolderid);

        if(!$newFolder)
        {

        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);

        $item = auth()->user()->posts()->create($request->all());

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('Post "%s" created!', $item->title)
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
        $this->validate($request, $this->validationRules);

        $item = $this->findOrAbort($id);

        $redirect = $this->checkAccessRights($item);

        if($redirect)
        {
            return $redirect;
        }
        /*
        $folder = auth()->user()->folders->find($request->input(6));

        if($folder)
        {

        }

        $item->folder()->associate($folder);
        */

        $item->update($request->all());

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('Post "%s" updated!', $item->title)
        ]);
    }
}
