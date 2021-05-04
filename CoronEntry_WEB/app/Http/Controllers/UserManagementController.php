<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index()
    {
        $sid = request()->session()->get('session_id');
        $users = User::select('*')->where('fk_placeid','=', $sid)->where('user_type','=', 'Default')->get();
        return view('user-management')->with('users',$users);
    }

    public function add(Request $request)
    {
        $sid = request()->session()->get('session_id');
        if (DB::table('users')->where('email', '=', $request->email)->where('user_code', '=', $request->code)->exists()) {
            $user = User::where('email', $request->email)->first();
            //dd($user);
            if($request->input('entry-class') != null){
                $user->entry_class = $request->input('entry-class');
            }
            $user->fk_placeid = $sid;
            $user->save();
           
        }
        
        // $user = new User;
        // $user->entry_class = $request->input('entry-class');
        // $user->user_code = $request->input('entry-code');
        // $user->name = $request->input('name');
        // $user->name = $request->input('name');
        // $user->fk_placeid = $sid;
        // $user->save();

        return redirect('/usermng')->with('success','Saved');
       
    }

    public function edit(Request $request, $id)
    {
     
        $user = User::find($id);
        $user->entry_class = $request->input('entry-class');
        $user->save();

        return redirect('/usermng')->with('success','Updated');
        //dd($request->input('entry-class'));
        //return view('user-management')->with('users',$users);
    }

    public function delete(Request $request, $id)
    {
     
        $users = User::find($id);      
        $users->delete();

        return redirect('/usermng')->with('success','Delete');
        //dd($request->input('entry-class'));
        //return view('user-management')->with('users',$users);
    }
}
