<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\TeamsController;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $team_wins = DB::table("teams")
        ->select([DB::raw('MAX(wins) AS wins'),'name'])
        ->groupBy('teams.id','name')
        ->orderBy('wins','DESC')
        ->limit(3)
        ->get();
        $colors = ['success','warning','info'];
        $matches =  DB::select(DB::raw('SELECT *, (select name from teams where id=matches.home) as `home_team`, (select logo from teams where id=matches.home) as `home_logo`, (select name from teams where id=matches.visitor) as `visitor_team`, (select logo from teams where id=matches.visitor) as `visitor_logo` from matches'));
        return view('home',compact('team_wins','colors','matches'));
    }
}
