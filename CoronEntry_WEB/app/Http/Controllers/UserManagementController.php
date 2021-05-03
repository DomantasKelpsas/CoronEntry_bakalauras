<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController extends Controller
{
    public function index()
    {

        $users = User::all();
        return view('user-management')->with('users',$users);
    }

    public function edit(Request $request, $id)
    {
     
        $users = User::find($id);
        $users->entry_class = $request->input('entry-class');
        $users->save();

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
