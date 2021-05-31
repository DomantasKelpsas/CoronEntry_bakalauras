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
        $unassigned_users = DB::table('unassigned_users')->join('users','users.user_code','=','unassigned_users.user_code')->where('unassigned_users.fk_placeid','=', $sid)->get();
        //dd($unassigned_users);
        //$unassigned_users = User::select('*')->where('user_code','=', $unassigned_user_code)->where('user_type','=', 'Default')->get();
        return view('user-management')->with('data',['users' => $users, 'unassigned_users' => $unassigned_users]);
    }

    public function add(Request $request)
    {
        $sid = request()->session()->get('session_id');
        //where('email', '=', $request->email)->
        if (DB::table('users')->where('user_code', '=', $request->code)->exists()) {
            $user = User::where('user_code', $request->code)->first();
            //dd($user);
            if($request->input('entry-class') != null){
                $user->entry_class = $request->input('entry-class');
            }
            $user->fk_placeid = $sid;
            $user->save();

            DB::table('unassigned_users')->where('user_code', '=', $request->code)->delete();
           
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
     
        $user = User::find($id);       
        $user->entry_class = "";
        $user->fk_placeid = null;
        $user->save();  
        

        return redirect('/usermng')->with('success','Delete');
        //dd($request->input('entry-class'));
        //return view('user-management')->with('users',$users);
    }
}
