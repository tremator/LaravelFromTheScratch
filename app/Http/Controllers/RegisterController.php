<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class RegisterController extends Controller
{
    public function create(){
        return view('register.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

       

        User::create($attributes); 

        session()->flash('success','Your account has been created.');

        return redirect('/');
    }
}
