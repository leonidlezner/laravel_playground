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
    protected $viewIndex = '';
    protected $viewCreate = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        assert($this->modelName != '');
        assert($this->indexRoute != '');
        assert($this->viewIndex != '');
        assert($this->viewCreate != '');

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

        $trashed = $this->modelName::onlyTrashed()->orderBy('id', 'desc')->get();

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
    public function createWithTitle($title)
    {
        return view($this->viewCreate)->with([
            'title' => $title
        ]);
    }
}