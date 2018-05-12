<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Post;

class CrudController extends Controller
{
    protected $modelName = '';
    protected $indexRoute = '';
    protected $resourceName = ['one' => '', 'many' => ''];
    protected $authExcept = ['index', 'show'];
    protected $viewFolder = '';
    protected $viewIndex = '';
    protected $viewCreate = '';
    protected $viewShow = '';
    protected $viewEdit = '';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if($this->viewFolder)
        {
            if(!$this->viewIndex) {
                $this->viewIndex = sprintf('%s.index', $this->viewFolder);
            }
            if(!$this->viewCreate) {
                $this->viewCreate = sprintf('%s.create', $this->viewFolder);
            }
            if(!$this->viewShow) {
                $this->viewShow = sprintf('%s.show', $this->viewFolder);
            }
            if(!$this->viewEdit) {
                $this->viewEdit = sprintf('%s.edit', $this->viewFolder);
            }
        }

        assert($this->modelName != '');
        assert($this->indexRoute != '');
        assert($this->viewIndex != '');
        assert($this->viewCreate != '');
        assert($this->viewShow != '');
        assert($this->viewEdit != '');

        $this->middleware('auth')->except($this->authExcept);
    }

    protected function findOrAbort($id, $includeTrashed = false)
    {
        if($includeTrashed)
        {
            $resource = $this->modelName::withTrashed()->find($id);
        }
        else
        {
            $resource = $this->modelName::find($id);
        }
        
        if(!$resource)
        {
            abort(404, sprintf('%s not found', $this->resourceName['one']));
        }

        return $resource;
    }

    protected function checkAccessRights($item)
    {
        if($item->user_id != auth()->user()->id) {
            return redirect()->route($this->indexRoute)->with('error', 
                sprintf('You are not authorized to modify the %s "%s"!', $this->resourceName['one'], $item->title));
        }

        return NULL;
    }

    protected function newItem()
    {
        $item = new $this->modelName();
        
        $item->user_id = auth()->user()->id;

        return $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->modelName::orderBy('id', 'desc')->get();

        if(auth()->check())
        {
            $trashed = $this->modelName::onlyTrashed()
                                ->orderBy('id', 'desc')
                                ->where('user_id', auth()->user()->id)
                                ->get();
        }
        else
        {
            $trashed = array();
        }

        return view($this->viewIndex)->with([
            'items' => $items,
            'trashed' => $trashed,
            'title' => $this->resourceName['many'],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->viewCreate)->with([
            'title' => sprintf('Create new %s', $this->resourceName['one'])
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
        $item = $this->findOrAbort($id);

        return view($this->viewShow)->with(compact('item'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->findOrAbort($id);

        $redirect = $this->checkAccessRights($item);

        if($redirect)
        {
            return $redirect;
        }

        return view($this->viewEdit)->with(compact('item'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = $this->findOrAbort($id);

        $redirect = $this->checkAccessRights($item);

        if($redirect)
        {
            return $redirect;
        }

        $title = $item->title;

        $item->delete();

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('%s "%s" deleted!', $this->resourceName['one'], $title)
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
        $item = $this->findOrAbort($id, true);

        $redirect = $this->checkAccessRights($item);

        if($redirect)
        {
            return $redirect;
        }

        $item->restore();

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('%s "%s" restored!', $this->resourceName['one'], $item->title)
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
        $item = $this->findOrAbort($id, true);
        
        $redirect = $this->checkAccessRights($item);

        if($redirect)
        {
            return $redirect;
        }

        $title = $item->title;

        $item->forceDelete();

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('%s "%s" removed!', $this->resourceName['one'], $title)
        ]);
    }
}