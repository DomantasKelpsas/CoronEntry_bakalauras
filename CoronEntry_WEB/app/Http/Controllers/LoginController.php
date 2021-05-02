<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
               'email' => 'required|email',
               'password' => 'required',
           ]
        );

       
        //    if(!auth()->attempt($request->only('email','password'))){
        //        return back()->with('status','Invalid login details');
        //    }

        // if (DB::table('users')->where('email', '=', $request->email)->exists() && DB::table('users')->where('password', '=', $request->password)->exists()
        // && DB::table('users')->where('user_type', 'Admin')->exists()) {
        //     return redirect()->route('home');       
        // } 
        // else return back()->with('status', 'Invalid login details');
        if (DB::table('users')->where('email', '=', $request->email)->where('password', '=', $request->password)->where('user_type', 'Admin')->exists()) {
            return redirect()->route('home');       
        } 
        else return back()->with('status', 'Invalid login details');
        
    }
}
