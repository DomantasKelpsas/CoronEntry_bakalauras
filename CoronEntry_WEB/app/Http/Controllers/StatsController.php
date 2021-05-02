<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Statistic;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        return view('stats');
    }

    public function makeChart()
    {
        //    $users = Statistic::select(DB::raw("COUNT(DISTINCT(user_id)) as count"))->get();
        $users = Statistic::select(DB::raw("COUNT(DISTINCT(user_id)) as count"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('count');
        $months = Statistic::select(DB::raw("Month(date) as month"))->whereYear('date',date('Y'))->groupBy(DB::raw("Month(date)"))->pluck('month');
        
        $data = array_fill(0,12,0);
        foreach ($months as $index => $month)
        $data[$month-1] = $users[$index];         
        return view('stats')->with('data', $data);
    }
}
