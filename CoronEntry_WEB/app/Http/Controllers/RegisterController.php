<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
               'name' => 'required',
               'placename' => 'required',
               'email' => 'required|email',
               'password' => 'required|confirmed',
           ]
        );

        $user = new User;

        DB::table('places')->insert([
            'name' => $request->placename]);
        $placeid = DB::table('places')->where('name', '=', $request->placename)->pluck('id');
        //dd($placeid) ;
        $user->name =  $request->name;
        $user->email =  $request->email;
        $user->password =  hash('sha256',$request->password);
        $user->entry_class = 'High';
        $user->user_type = 'Admin';
        $user->user_code = substr(hash('sha256',microtime()),rand(0,26),6);
        $user->fk_placeid = $placeid[0];
        $user->save();

        $request = request();
        $request->session()->put('session_id', $placeid[0]);
        $request->session()->save();

            return redirect()->route('home')->with('user_name',  $request->name);  ;


        // if (DB::table('users')->where('email', '=', $request->email)->where('password', '=', hash('sha256',$request->password))->where('user_type', 'Admin')->exists()) {
        //     $user_id = DB::table('users')->where('email', '=', $request->email)->pluck('fk_placeid');
        //     //dd($user_id[0]);
        //     //session(['session_id' => $user_id[0]]);
        //     $request = request();
        //     $request->session()->put('session_id', $user_id[0]);
        //     $request->session()->save();
        //     return redirect()->route('home');       
        // } 
        // else return back()->with('status', 'Invalid login details');
        
    }

}
