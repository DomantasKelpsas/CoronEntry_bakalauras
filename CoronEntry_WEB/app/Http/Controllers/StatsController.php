<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Statistic;
use App\Models\User;
use App\Models\Entrypoint;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        return view('stats');
    }

    public function makeChart()
    {          
        $sid = request()->session()->get('session_id');   
        //$usercount = Statistic::select(DB::raw("COUNT(DISTINCT(user_id)) as count"))->where('fk_placeid','=',  $sid)->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('count');
        $usercount = Statistic::select(DB::raw("COUNT(user_id) as count"))->where('fk_placeid','=',  $sid)->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('count');
        $months = Statistic::select(DB::raw("Month(date) as month"))->where('fk_placeid','=',  $sid)->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('month');       
        $users = User::select('*')->where('fk_placeid','=', $sid)->get();
        $eps = Entrypoint::select('*')->where('fk_placeid','=', $sid)->get();
       
        $chartdata = array_fill(0,12,0);
        foreach ($months as $index => $month)
        $chartdata[$month-1] = $usercount[$index];         
        return view('stats')->with('data', ['users' => $users,'eps'=>$eps,'chartdata' =>$chartdata]);
    }

    public function makeChartByDate(Request $request)
    {          
        $this->validate(
            $request,
            [
               'datefrom' => 'before:dateto',               
           ]
        );

        $sid = request()->session()->get('session_id');   
        //$usercount = Statistic::select(DB::raw("COUNT(DISTINCT(user_id)) as count"))->where('fk_placeid','=',  $sid)->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('count');
        $usercount = Statistic::select(DB::raw("COUNT(user_id) as count"))->where('fk_placeid','=',  $sid)->where('date','>=',$request->datefrom)->where('date','<=',$request->dateto)->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('count');
        $months = Statistic::select(DB::raw("Month(date) as month"))->where('fk_placeid','=',  $sid)->where('date','>=',$request->datefrom)->where('date','<=',$request->dateto)->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('month');       
        //dd($usercount);
        $users = User::select('*')->where('fk_placeid','=', $sid)->get();
        $eps = Entrypoint::select('*')->where('fk_placeid','=', $sid)->get();
       
        $chartdata = array_fill(0,12,0);
        foreach ($months as $index => $month)
        $chartdata[$month-1] = $usercount[$index];         
        return view('stats')->with('data', ['users' => $users,'eps'=>$eps,'chartdata' =>$chartdata]);
    }

    public function singleUserStats(Request $request, $id){       
        
        $userstat = Statistic::select('*')->join('entrypoints','statistics.ep_id','=','entrypoints.id')->where('statistics.user_id','=', $id)->get();     
        return response()->json($userstat);
    }
    public function singleEPStats(Request $request, $id){       
        
        $epstat = Statistic::select('*')->join('users','statistics.user_id','=','users.id')->where('statistics.ep_id','=', $id)->get();     
        return response()->json($epstat);
    }

    // public function badtempNotification(Request $request){
    //     //$response = Http::post('localhost:8000/badtemp-notification',['temp'=>$request]);
    //     dd($request);
    //     return redirect('/epmng');       
    // }
    public function badtemp(Request $request){
       return Statistic::all();
    }
}
