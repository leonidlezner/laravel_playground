<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoldersController extends CrudController
{
    protected $modelName = '\App\Folder';
    protected $indexRoute = 'folders.index';
    protected $resourceName = ['one' => 'Folder', 'many' => 'Folders'];
    protected $viewFolder = 'folders';

    
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
        ]);

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
        $this->validate($request, [
            'title' => 'required'
        ]);

        $item = $this->findOrAbort($id);

        $redirect = $this->checkAccessRights($item);

        if($redirect)
        {
            return $redirect;
        }

        $item->update($request->all());

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('Folder "%s" updated!', $item->title)
        ]);
    }
}
