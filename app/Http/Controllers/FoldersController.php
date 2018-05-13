<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoldersController extends CrudController
{
    protected $model = \App\Folder::class;
    protected $indexRoute = 'folders.index';
    protected $resourceName = ['one' => 'Folder', 'many' => 'Folders'];
    protected $viewFolder = 'folders';
    protected $validationRules = ['title' => 'required'];

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules);

        $this->authorize('create', $this->model);

        $item = auth()->user()->folders()->create($request->all());

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('Folder "%s" created!', $item->title)
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

        $this->authorize('update', $item);

        $item->update($request->all());

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('Folder "%s" updated!', $item->title)
        ]);
    }
}
