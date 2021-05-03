<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        return view('stats');
    }

    public function makeChart()
    {
        
       
        $usercount = Statistic::select(DB::raw("COUNT(DISTINCT(user_id)) as count"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('count');
        $months = Statistic::select(DB::raw("Month(date) as month"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('month');
        $users = User::all();

        
        $chartdata = array_fill(0,12,0);
        foreach ($months as $index => $month)
        $chartdata[$month-1] = $usercount[$index];         
        return view('stats')->with('data', ['users' => $users,'chartdata' =>$chartdata]);
    }

    public function singleUserStats(Request $request, $id){
        $userstat = Statistic::where('user_id','=', $id)->get();
        //dd($userstat);
        return response()->json($userstat);
    }
}