<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request\validate;


use App\Models\Lot;



class LotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->validate($request, [
            'numero' => 'required|string|unique:App\Models\Lot',
            'variete_id' => 'required',
            'quantite'=>'required',
            'chambre_id' => 'required',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
    
        ]);
       
        $imageName='';

        if (!is_null($request->image)){
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/lots'), $imageName);
        }
       
        Lot::updateOrCreate(
            [
              'id' => $request->id
            ],
            [
              'numero' => $request->numero,
              'capacite' => $request->capacite,
              'image'=> $imageName,
              'quantite'=>$request->quantite,
              'variete_id'=>$request->variete_id,
              'chambre_id'=>$request->chambre_id

            ]
          );
          return response()->json('Ajouté avec succes',201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lot = Lot::find($id);
        return response()->json($lot,201);

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
    }
    /**
     * update lot using post
     */
    public function updateLot(Request $request, $id)
    {
        $lot = Lot::findOrFail($id);
        //$lotData = array_filter($request->all());
        $this->validate($request, [
            'numero'     => 'required',
            'variete_id' => 'required',
            'quantite'   =>'required',
            'chambre_id' => 'required',    
        ]); 
        $imageName=$lot->image;
        if (!is_null($request->image)){
            unlink("images/lots/".$$lot->image);
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/lots'), $imageName);
        } 
        Lot::updateOrCreate(
            [
              'id' => $request->id
            ],
            [
              'numero' => $request->numero,
              'capacite' => $request->capacite,
              'image'=> $imageName,
              'quantite'=>$request->quantite,
              'variete_id'=>$request->variete_id,
              'chambre_id'=>$request->chambre_id
            ]
          );  
        return response()->json('Modifié avec succes',201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $lot = Lot::find($id);
        $image=$lot->image;
        if(!is_null($image))
            unlink("images/lots/".$image);
        $lot->delete();
        // TODO supprimer l'image dans le dossier       

        return response()->json([
            'message' => 'Supprimées avec succès!'
        ]);
    }
}
