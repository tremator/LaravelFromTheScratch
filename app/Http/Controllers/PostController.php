<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{

    public function index(){

        return view('posts',[
            'posts' => Post::latest()->filter(request(['search','category','author']))->paginate(6)->withQueryString() ,
            
        ]);
    }

    public function show(Post $post){
        return view('post',[
            'post' => $post
        ]);
    }

    public function create(){

        return view('createPost');
    }

    public function store(){

       

        $attributes = request()->validate([
            'title' => 'required',
            'slug' => ['required'],
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => ['required']
        ]);

      

        $attributes['user_id'] = auth()->id();
        $attributes['thumpnail'] = request()->file('thumpnail')->store('thumbnails');

        Post::create($attributes);

        return redirect('/');

    }
    
}
