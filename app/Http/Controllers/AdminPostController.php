<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class AdminPostController extends Controller
{
    public function index(){
        return view('adminPosts',[
            'posts' => Post::paginate(50)
        ]);
    }


    public function edit(Post $post){
        return view('adminPostEdit',['post' => $post]);
    }

    public function update(Post $post){
        
        
        $attributes = request()->validate([
            'title' => 'required',
            'slug' => ['required'],
            'thumpnail' => 'required',
            'excerpt' => 'required',
            'body' => 'required',
            'category_id' => ['required']
        ]);

        if (isset($attributes['thumpnail'])) {
            $attributes['thumpnail'] = request()->file('thumpnail')->store('thupnails');
        }

        $post->update($attributes);

        return back()->with('success','Post updated');

    }

    public function destroy(Post $post){
        $post->delete();

        return back();
    }

}
