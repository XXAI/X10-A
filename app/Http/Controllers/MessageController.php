<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store()
    {
    	request()->validate([
    		'name' => 'required',
    		'email' => 'required',
    		'subject' => 'required',
    		'content' => 'required|min:3'
    	]);
    	return back()->with('status','Recibimos tu mensaje.');
    }
}
