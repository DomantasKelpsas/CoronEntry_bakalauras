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
        
        if (DB::table('users')->where('email', '=', $request->email)->where('password', '=', hash('sha256',$request->password))->where('user_type', 'Admin')->exists()) {
            $user_id = DB::table('users')->where('email', '=', $request->email)->pluck('fk_placeid');
            $user_name = DB::table('users')->where('email', '=', $request->email)->pluck('name');
            //dd($user_id[0]);
            //session(['session_id' => $user_id[0]]);
            $request = request();
            $request->session()->put('session_id', $user_id[0]);
            $request->session()->save();
            return redirect()->route('home')->with('user_name',  $user_name);       
        } 
        else return back()->with('status', 'Invalid login details');
        
    }

    public function logout(){
        
        $request = request();
        $request->session()->forget('session_id');
        return view('login');
    }
}
