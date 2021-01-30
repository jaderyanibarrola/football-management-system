<?php

namespace App\Http\Controllers;

use App\Models\Players;
use DB;
use Illuminate\Http\Request;
use Redirect, Response;
use Carbon\Carbon;
use Validator;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $players = Players::get()->toArray();
        return view('players', compact('players'));
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
        $validation = [
            'first_name' => 'required',
            'last_name' => 'required',
            'age' => 'required|numeric|min:15'
        ];
        
        if($request->file('photo')!==null){
            $validation['photo'] = 'required|file|max:2048|mimes:jpeg,png,gif';
        }
            
        $request->validate($validation);
        
        $firstname = $request->first_name;
        $lastname = $request->last_name;
        $age = $request->age;
        $weight = (isset($request->weight)) ? intval($request->weight) : '0';
        $height = (isset($request->height)) ? intval($request->height) : '0';
        $active = (isset($request->active)) ? intval($request->active) : '0';
        $file = $request->file('photo');
        $path = public_path('/uploads');

        $data = [
            'first_name' => $firstname,
            'last_name' => $lastname,
            'age' => $age,
            'weight' => $weight,
            'height' => $height,
            'active' => $active
        ];
        
        if($file){
            $newfilename = date('YmdHis').'-'.$file->getClientOriginalName();
            if(!$file->move($path, $newfilename)) {
                return redirect()->back()->with([
                    'status' => false,
                    'response' => 'Error on uploading file'
                ]);
            }
            else
                $data['photo'] = $newfilename;
        }
        
        if(!isset($request['id'])){
            Players::create($data);
            return redirect()->back()->with([
                'status' => true,
                'response' => 'Player created successfully'
            ]);
        }
        else{
                        
            Players::where('id', $request['id'])->update($data);
                        
            return redirect()->back()->with([
                'status' => true,
                'response' => 'Player updated successfully'
            ]);
        }

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
        $data = Players::where('id', $id)->first()->toArray();

        //unlink photo
        if(file_exists('uploads/'.$data['photo'])){
            unlink('uploads/'.$data['photo']);
            Players::where(['id'=>$id])->delete();
        }
        return redirect()->back()
            ->with([
                'status' => true,
                'response' => 'Player removed successfully'
            ]
        );
    }
	
	public function get_data($id)
    {
        $players = Players::where('id','=',$id)->get()->first();
		if($players){
			return json_encode([
				'status'=>true,
				'response'=>$players
			]);
		}
		else{
			return json_encode([
				'status'=>false,
				'response'=>null
			]);
		}
    }
    
    public function get_players(){
        $players = Players::get()->toArray();
        return response()->json($players, 200);
    }
	
}
