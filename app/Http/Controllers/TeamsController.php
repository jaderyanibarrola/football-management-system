<?php

namespace App\Http\Controllers;

use App\Models\Teams;
use DB;
use Illuminate\Http\Request;
use Redirect, Response;
use Carbon\Carbon;
use Validator;

class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Teams::get()->toArray();
        return view('teams', compact('teams'));
    }
    
    public function download()
    {
        $filename = "template.csv";
        $file = Storage::disk('public')->get($filename);
        return (new Response($file, 200))
              ->header('Content-Type', 'text/csv');
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
        $validation = ['name' => 'required'];
        
        if($request->file('logo')!==null){
            $validation['logo'] = 'required|file|max:2048|mimes:jpeg,png,gif';
        }
            
        $request->validate($validation);
        
        $name = $request->name;
        $wins = (isset($request->wins)) ? intval($request->wins) : '0';
        $losses = (isset($request->losses)) ? intval($request->losses) : '0';
        $file = $request->file('logo');
        $path = public_path('/uploads');

        $data = [
            'name' => $name,
            'wins' => $wins,
            'losses' => $losses
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
                $data['logo'] = $newfilename;
        }
        
        if(!isset($request['id'])){
            Teams::create($data);
            return redirect()->back()->with([
                'status' => true,
                'response' => 'Team created successfully'
            ]);
        }
        else{
            $data['wins'] = $request['wins'];
            $data['losses'] = $request['losses'];
            $data['active'] = $request['active'];
                        
            Teams::where('id', $request['id'])->update($data);
                        
            return redirect()->back()->with([
                'status' => true,
                'response' => 'Team updated successfully'
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
        $data = Teams::where('id', $id)->first()->toArray();

        //unlink photo
        if($data['logo']!==null && file_exists('uploads/'.$data['logo'])){
            unlink('uploads/'.$data['logo']);
        }
        
        Teams::where(['id'=>$id])->delete();
        
        return redirect()->back()
            ->with([
                'status' => true,
                'response' => 'Team removed successfully'
            ]
        );
    }
	
	public function get_data($id)
    {
        $team = Teams::where('id','=',$id)->get()->first();
		if($team){
			return json_encode([
				'status'=>true,
				'response'=>$team
			]);
		}
		else{
			return json_encode([
				'status'=>false,
				'response'=>null
			]);
		}
    }
	
	public function import(Request $request)
    {
        $file = $request->file('file');
        if($file!==null){
            $path = public_path('/uploads/imports');
            $newfilename = date('YmdHis').'-'.$file->getClientOriginalName();
            if($file->move($path, $newfilename)) {
                //read csv and upload
                $filename = $path.'/'.$newfilename;
                $csv = array_map('str_getcsv', file($filename));
                if($csv!=null && is_array($csv) && sizeof($csv)>0){
                    $data = [];
                    $temp = [];
                    $count = 0;
                    foreach($csv as $team){
                        if(isset($team[0])){
                            $tmp['name'] = $team[0];
                            $count++;
                        }
                        else
                            continue;
                            
                        if(isset($team[1]))
                            $tmp['wins'] = intval($team[1]);
                        if(isset($team[2]))
                            $tmp['losses'] = intval($team[2]);
                        if(isset($team[3]))
                            $tmp['active'] = intval($team[3]);
                        
                        array_push($data, $tmp);
                        unset($tmp);
                    }
                    
                    Teams::upsert($data, ['name'], ['wins','losses','active']);
                                
                    return redirect()->back()->with([
                        'status' => true,
                        'response' => $count.' teams was successfully imported!'
                    ]);
                }
            }
       }
    }
	
}
