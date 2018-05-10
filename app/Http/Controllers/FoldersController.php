<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoldersController extends CrudController
{
    protected $modelName = '\App\Folder';
    protected $indexRoute = 'folders.index';
    protected $resourceName = ['one' => 'Folder', 'many' => 'Folders'];
    protected $viewIndex = 'folders.index';
    protected $viewCreate = 'folders.create';

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return parent::createWithTitle('Create a new Folder');
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
        ]);

        $item = $this->newItem();

        $item->title = $request->input('title');

        $item->save();

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('Folder "%s" created!', $item->title)
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
