<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index() {
        $title = 'Welcome to my blog';

        return view('pages.index')->with(array(
            'title' => $title
        ));
    }

    public function about() {
        $title = 'About my blog';

        return view('pages.about')->with(array(
            'title' => $title
        ));
    }
}
