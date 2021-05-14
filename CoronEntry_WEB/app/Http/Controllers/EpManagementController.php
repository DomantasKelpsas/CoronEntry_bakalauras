<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrypoint;
use Illuminate\Support\Facades\DB;

class EpManagementController extends Controller
{
    public function index()
    {

        $sid = request()->session()->get('session_id');
        $eps = Entrypoint::select('*')->where('fk_placeid','=', $sid)->get();
        return view('ep-management')->with('eps',$eps);
    }

    public function add(Request $request)
    {
        
        $sid = request()->session()->get('session_id');
        if (DB::table('entrypoints')->where('entry_code', '=', $request->code)->exists()) {
            $ep = Entrypoint::where('entry_code', $request->code)->first();            
            if($request->input('entry-class') != null){
                $ep->entry_class = $request->input('entry-class');
            }
            if ($request->input('name') != null){
                $ep->name = $request->input('name');
            }
            $ep->fk_placeid = $sid;
            $ep->save();
           
        }
       
        return redirect('/epmng')->with('success','Saved');
       
    }

    public function edit(Request $request, $id)
    {
     
        $eps = Entrypoint::find($id);
        $eps->entry_class = $request->input('entry-class');
        $eps->name = $request->input('name');
        $eps->save();

        return redirect('/epmng')->with('success','Updated');
        
    }

    public function delete(Request $request, $id)
    {
     
        $eps = Entrypoint::find($id);
        $eps->entry_class = "";
        $eps->name = "";
        $eps->fk_placeid = null;
        $eps->save();      
        // $eps->delete();

        return redirect('/epmng')->with('success','Delete');
        //dd($request->input('entry-class'));
        //return view('user-management')->with('users',$users);
    }
}
