<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/boo', function () {
    return "boo";
});

Route::get('/', 'PagesController@index')->name('home');
Route::get('/about', 'PagesController@about')->name('about');

Route::resource('posts', 'PostsController');
Route::post('/posts/{post}/restore', 'PostsController@restore')->name('posts.restore');
Route::post('/posts/{post}/forcedelete', 'PostsController@force_delete')->name('posts.force_delete');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
