<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    public function destroy(){
        Auth::logout();

        return redirect('/')->with('succes','BYE BYE');
    }


    public function create(){
        return view('sessions.create');
    }

    public function store(){

        $attributes = request()->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($attributes)) {
            return redirect('/')->with('success', 'Welcome Back');
        }

        return back()->withInput()->withErrors(['email' => 'Your Provided Credentials could not be verified']);

    }
}
