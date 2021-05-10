<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ReportController extends Controller
{
    /**
     * Find a listing of the resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    { 
        //validation
        $rules = [
            'dateDebut' => 'required|date',
            'dateFin' => 'required|date',            
        ];
        $messages = [
            "dateDebut.required" => "Champ obligatoire",
            "dateFin.required" => "Champ obligatoire",
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
        //requiste
       // $type=$request->input('typeClient');
        //if (strcmp($type,"Tous")==0) $type='';
        
        $report = DB::table('streams')                     
            ->select('streams.*')
            ->whereBetween('streams.created_at', [$request->input('dateDebut'), $request->input('dateFin')])           
            ->get();         

        return response()->json($report,201);
    }



}