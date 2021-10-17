<?php

namespace App\Http\Controllers;

use App\services\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function __invoke()
    {
        try {
            $newsLetter = new Newsletter();
    
            $newsLetter->subscribe(request('email'));
    
            return redirect('/')->with('success','You are now signed up for our news letter');
        } catch (\Exception $th) {
            return redirect('/')->with('success','Something went wrong');
        }
        
    }
}
