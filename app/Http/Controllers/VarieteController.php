<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Variete;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class VarieteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variets = Variete::all();
        return response()->json($variets,201);
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

        $rules = [
           'libelle'        => 'required|string|unique:App\Models\Variete',
           'temp_reference' => 'required',
           'hum_reference'  => 'required',
           'image'          => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
       ];
       $messages = [
           "libelle.unique"             => "La libellé doit être unique",
           "temp_reference.required"    => "Champ obligatoire",
           "hum_reference.required"     => "Champ obligatoire",
           "image.mimes"                => "images accetpées:jpeg,png,jpg,gif,svg"
       ];
       $validator = Validator::make($request->all(), $rules, $messages);
       if ($validator->fails()) {
           return response($validator->errors(), 400);
       }
       $imageName='';
       if (!is_null($request->image)){
           $imageName = time() . '.' . $request->image->extension();
           $request->image->move(public_path('images/uploads/varietes/'), $imageName);
       }
       $variete=new Variete();
       $variete->libelle= $request->get('libelle');
       $variete->temp_reference= $request->get('temp_reference');
       $variete->hum_reference= $request->get('hum_reference');
       $variete->image= $imageName;
       $variete->save();

       /*Variete::updateOrCreate(
           [
                'id' => $request->id
           ],
           [
             'libelle' => $request->libelle,
             'temp_reference' => floatval($request->temp_reference),
             'hum_reference' => $request->hum_reference,             
             'image'=> $imageName
           ]
         );*/

         return response()->json('Ajoutée avec succes',201);

   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $variete = Variete::find($id);
        return response()->json($variete,201);
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
     * update lot using post
     */
    public function updateVariete(Request $request, $id)
    {

        $this->validate($request, [
           'libelle'        => 'required',
           'temp_reference' => 'required',
           'hum_reference'  => 'required',
        ]); 

        $variete = Variete::findOrFail($id);
        $imageName=$variete->image;       

        if (!is_null($request->image)){
            if (!is_null($imageName))
            {
                unlink("images/uploads/varietes/".$imageName);
            } 

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path("images/uploads/varietes/"), $imageName);
        } 

       $variete->libelle= $request->get('libelle');
       $variete->temp_reference= $request->get('temp_reference');
       $variete->hum_reference= $request->get('hum_reference');
       $variete->image= $imageName;
       $variete->save();  
        return response()->json('Modifiée avec succes',201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variete = Variete::find($id);
        $image=$variete->image;
        if(!is_null($image))
            unlink("images/uploads/varietes/".$image);
        $variete->delete();
        return response()->json([
        'message' => 'Supprimées avec succès!'
        ]);
    }
}
