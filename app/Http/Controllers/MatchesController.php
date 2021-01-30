<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect, Response;
use Carbon\Carbon;
use Validator;
use App\Models\Teams;
use App\Models\Players;
use App\Models\Lineups;
use App\Models\Matches;

class MatchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lineups = Lineups::
            select('lineups.id as lid','teams.id as tid','teams.*','players.*')
            ->join('teams', 'teams.id','=','lineups.team_id')
            ->join('players', 'players.id','=','lineups.player_id')
            ->get()
            ->keyBy('tid');
        $matches =  DB::select(DB::raw('SELECT *, (select name from teams where id=matches.home) as `home_team`, (select logo from teams where id=matches.home) as `home_logo`, (select name from teams where id=matches.visitor) as `visitor_team`, (select logo from teams where id=matches.visitor) as `visitor_logo` from matches'));
        
        return view('matches', compact('lineups', 'matches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['home']==$request['visitor']){
            return redirect()->back()->with([
                'status' => false,
                'response' => 'A same team match cannot be accepted!'
            ]);
        }
        
        /*$date_now = date('Y-m-d H:i:s');
        $scheduled_date = date('y-m-d H:i:s', strtotime($request['schedule_date']));
        if($date_now < $scheduled_date){
            return redirect()->back()->with([
                'status' => false,
                'response' => 'Schedule date is in the past!'
            ]);
        }*/
            
        $action = '';
        if(!isset($request['id']) && $request['id']==null){
            //insert
            Matches::create([
                'home'=>$request['home'],
                'visitor'=>$request['visitor'],
                'schedule_date'=>date('y-m-d H:i:s', strtotime($request['schedule_date'])),
            ]);
            $action = 'added';
        }
        else{
            //update
            Lineups::where('id', $request['id'])->update([
                'home'=>$request['home'],
                'visitor'=>$request['visitor'],
                'schedule_date'=>date('y-m-d H:i:s', strtotime($request['schedule_date'])),
            ]);
            $action = 'updated';
        }
        
        return redirect()->back()->with([
            'status' => true,
            'response' => 'A match was '.$action.' successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Matches::where(['id'=>$id])->delete();
        return redirect()->back()
            ->with([
                'status' => true,
                'response' => 'A match was removed successfully'
            ]
        );
    }
}
