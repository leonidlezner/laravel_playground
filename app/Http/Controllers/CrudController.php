<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrudController extends Controller
{
    protected $model = '';
    protected $indexRoute = '';
    protected $resourceName = ['one' => '', 'many' => ''];
    protected $authExcept = ['index', 'show'];
    protected $viewFolder = '';
    protected $viewIndex = '';
    protected $viewCreate = '';
    protected $viewShow = '';
    protected $viewEdit = '';
    protected $validationRules = [];
    protected $items_per_page = 10;
    
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

        assert($this->model != '');
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
            $resource = $this->model::withTrashed()->find($id);
        }
        else
        {
            $resource = $this->model::find($id);
        }
        
        if(!$resource)
        {
            abort(404, sprintf('%s not found', $this->resourceName['one']));
        }

        return $resource;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->model::orderBy('id', 'desc')->paginate($this->items_per_page);

        if(auth()->check())
        {
            $trashed = $this->model::onlyTrashed()
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
        $this->authorize('create', $this->model);

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

        if(!in_array('show', $this->authExcept))
        {
            $this->authorize('view', $item);
        }

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

        $this->authorize('update', $item);

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

        $this->authorize('delete', $item);

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

        $this->authorize('delete', $item);

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

        $this->authorize('delete', $item);

        $title = $item->title;

        $item->forceDelete();

        return redirect()->route($this->indexRoute)->with([
            'success' => sprintf('%s "%s" removed!', $this->resourceName['one'], $title)
        ]);
    }
}