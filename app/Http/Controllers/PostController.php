<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;

use Illuminate\Http\Request;
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
    
}
