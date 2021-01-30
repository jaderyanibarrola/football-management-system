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

class LineupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Teams::where([
                ['teams.active', '=', 1],
            ])->get()->toArray();
        $players_available = Lineups::pluck('player_id')->all();
        $players = Players::whereNotIn('id', $players_available)
            ->where([
                ['players.active', '=', 1],
            ])->get()->toArray();
        //DB::enableQueryLog();
        $lineups = DB::table('lineups')
            ->select('lineups.id as lid','teams.*','players.*')
            ->join('teams', 'teams.id','=','lineups.team_id')
            ->join('players', 'players.id','=','lineups.player_id')
            ->get();
        //dd(DB::getQueryLog()); 
        return view('lineups', compact('teams','players','lineups'));
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
        $action = '';
        if(!isset($request['id']) && $request['id']==null){
            //insert
            Lineups::create([
                'team_id'=>$request['team_id'],
                'player_id'=>$request['player_id'],
            ]);
            $action = 'added';
        }
        else{
            //update
            Lineups::where('id', $request['id'])->update([
                'player_id'=>$request['player_id'],
            ]);
            $action = 'substituted';
        }
        
        return redirect()->back()->with([
            'status' => true,
            'response' => 'A lineup was '.$action.' successfully'
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
        Lineups::where(['id'=>$id])->delete();
        return redirect()->back()
            ->with([
                'status' => true,
                'response' => 'A lineup was removed successfully'
            ]
        );
    }    
	
	public function get_data($id)
    {
        $lineups = Lineups::where('id','=',$id)->get()->first();
		if($lineups){
			return json_encode([
				'status'=>true,
				'response'=>$lineups
			]);
		}
		else{
			return json_encode([
				'status'=>false,
				'response'=>null
			]);
		}
    }
    
    public function get_lineups(){
        $lineups = DB::table('lineups')
            ->select('lineups.id as lid','teams.*','players.*')
            ->join('teams', 'teams.id','=','lineups.team_id')
            ->join('players', 'players.id','=','lineups.player_id')
            ->get();
        return response()->json($lineups, 200);
    }
    
    public function substitute(Request $request, $id){
        if(isset($request['team_id']) && isset($request['player_old_id']) && isset($request['player_new_id'])){
            $team_id = intval($request['team_id']);
            $player_old_id = intval($request['player_old_id']);
            $player_new_id = intval($request['player_new_id']);
            
            if(Lineups::where('id',$id)->exists()){
                if(Teams::where('id',$team_id)->exists() && Players::where('id',$player_old_id)->exists() && Players::where('id',$player_new_id)->exists()){
                    Lineups::where('id',$id)->update(['player_id'=>$player_new_id]);
                    return response()->json(['substitute was successful'], 200);
                }
                else{
                    return response()->json(['Team id or player ids does not exist'], 200);
                }
            }
            else{
                return response()->json(['Lineup does not exist'], 200);
            }
            exit;
            /*$data = Lineups::where([
                'team_id','=',$team_id,
                'player_id','=',$player_old_id
            ])->get()->toArray();
            dd(DB::getQueryLog());
            dd($data);*/
            return response()->json($lineups, 200);
        }
        else{
            return response()->json(['All 3 parameters are required'], 200);
        }                                                                           
    }
}
