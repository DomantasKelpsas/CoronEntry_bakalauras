<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrypoint;
class EpManagementController extends Controller
{
    public function index()
    {

        $sid = request()->session()->get('session_id');
        //dd( $sid);
        $eps = Entrypoint::select('*')->where('fk_placeid','=', $sid)->get();
        return view('ep-management')->with('eps',$eps);
    }

    public function edit(Request $request, $id)
    {
     
        $eps = Entrypoint::find($id);
        $eps->entry_class = $request->input('entry-class');
        $eps->name = $request->input('name');
        $eps->save();

        return redirect('/epmng')->with('success','Updated');
        //dd($request->input('entry-class'));
        //return view('user-management')->with('users',$users);
    }

    public function delete(Request $request, $id)
    {
     
        $eps = Entrypoint::find($id);      
        $eps->delete();

        return redirect('/epmng')->with('success','Delete');
        //dd($request->input('entry-class'));
        //return view('user-management')->with('users',$users);
    }
}
