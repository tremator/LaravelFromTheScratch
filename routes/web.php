<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('posts');
});

Route::get('/hello', function () {
    return view('welcome');
});

Route::get('/helloWorld', function () {
    return "Hello World";
});

Route::get('/json', function () {
    return ['message' => 'Hello World'];
});

Route::get('/posts/{post}', function ($slug) {
    $path = __DIR__ . "/../resources/posts/{$slug}.html";
    if(! file_exists($path)){
        dd('File dosnt exists');

    }

    $post =  cache()->remember("posts.{$slug}", now()->addHour(), function() use ($path){
        var_dump('file gets content');
        return file_get_contents($path);
    });

    
    return view('post',[
        'post' => $post
    ]);;
})->whereAlpha('post');