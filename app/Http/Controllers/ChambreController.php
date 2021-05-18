<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Chambre;
use App\Models\Lot;

use Illuminate\Http\Request\validate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class chambreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $chambres = Chambre::all();
        return response()->json($chambres,201);
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
     * retourner les details de la chambre par id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chambres = DB::table('chambres')
            ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
            ->join('streams', 'streams.carte_code', '=', 'cartes.code')
            ->leftjoin('lots', 'lots.chambre_id', '=', 'chambres.id')
            ->select( 'chambres.numero','chambres.capacite','chambres.image',
                'temperature', 'humedite', 'etat_porte', 'etat_compresseur', 'etat_evaporateur','streams.created_at' )
            ->where('chambres.id','=' ,$id)
            ->orderByDesc('streams.created_at')->first();

        return response()->json($chambres,201);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chambre = Chambre::find($id);
        $image=$chambre->image;
        if(!is_null($image))
            unlink("images/uploads/chambres/".$image);
        $chambre->delete();
        return response()->json([
        'message' => 'Supprimées avec succès!'
        ]);
    }
  /**
     * update lot using post
     */
    public function updateChambre(Request $request, $id)
    {

        $this->validate($request, [
            'numero'     => 'required',
            'capacite' => 'required', 
        ]); 

        $chambre = Chambre::findOrFail($id);

        $imageName=$chambre->image;        

        if (!is_null($request->image)){
            if ($imageName)
            {
                unlink("images/uploads/chambres/".$imageName);

            } 
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/uploads/chambres'), $imageName);
        } 
        Chambre::updateOrCreate(
            [
              'id' => $request->id
            ],
            [
              'numero' => $request->numero,
              'capacite' => $request->capacite,
              'image'=> $imageName
            ]
          );  
        return response()->json('Modifiée avec succes',201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         //TODO à verifier le validation
         $rules = [
            'numero' => 'required|string|unique:App\Models\Chambre',
            'capacite' => 'required|numeric',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
        $messages = [
            "numero.unique" => "La référence doit être unique",
            "capacite.required" => "Champ obligatoire",
            "image.mimes" => "images accetpées:jpeg,png,jpg,gif,svg"
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }
        $imageName='';
        if (!is_null($request->image)){
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/uploads/chambres'), $imageName);
        }
        Chambre::updateOrCreate(
            [
              'id' => $request->id
            ],
            [
              'numero' => $request->numero,
              'capacite' => $request->capacite,
              'image'=> $imageName
            ]
          );

          return response()->json('Ajoutée avec succes',201);

    }

    ////////////////// Capacite //////////////////////////////////:
    public function getCapacite($id)
    {
        $chambres = DB::table('chambres')
            ->join('lots', 'lots.chambre_id', '=', 'chambres.id')
            ->select( 'chambres.numero','chambres.capacite',
            DB::raw('ROUND(IFNULL(100*sum(lots.Quantite)/chambres.capacite,0),2) as taux'),
            DB::raw('IFNULL(sum(lots.Quantite),0) as capcite_reel'))
            ->where('chambres.id','=' ,$id)
            ->first();

        return response()->json($chambres,201);
    }
    public function getCapaciteAll()
    {
        $chambres = DB::table('chambres')
        ->leftJoin('lots', 'lots.chambre_id', '=', 'chambres.id')
        ->select( 'chambres.id','chambres.numero','chambres.capacite',
        
        DB::raw('ROUND(IFNULL(100*sum(lots.Quantite)/chambres.capacite,0),2) as taux'),
         DB::raw('ROUND(IFNULL(sum(lots.Quantite),0),2) as capcite_reel'))
        ->groupBy('chambres.id')
        ->get();

        return response()->json($chambres,201);
    }

    /////////////////////////////////// Temp & hum ///////////////////////////////////

    public function getTempHum($id,$interval)
    {
        $newTime = Carbon::now()->subMinutes($interval);
        $chambres = DB::table('chambres')
            ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
            ->join('streams', 'streams.carte_code', '=', 'cartes.code')
            ->select( 'temperature', 'humedite','streams.created_at')
            ->where('streams.created_at','>=' ,$newTime)
            ->where('chambres.id','=',$id)
            ->orderByDesc('streams.created_at')
            ->get();
        return response()->json($chambres,201);
    }

    /////////////////////////////////// Lots ///////////////////////////////////
    public function getLots($id)
    {
        $chambres = DB::table('chambres')
            ->join('lots', 'lots.chambre_id', '=', 'chambres.id')
            ->join('varietes', 'lots.variete_id', '=', 'varietes.id')
            ->select( 'chambres.id', 'lots.*','varietes.libelle')
            ->where('chambres.id','=' ,$id)
            ->get();

        return response()->json($chambres,201);
    }

    // ajouter un lot à chambre
    public function addLots(Request $request){
        $data = $request->validate([
            'numero'     => ['required', 'string'],
            'quatite'    => 'required',
            'libelle' => ['required', 'string'],
            'chambre_id' => 'required',
            'variete_id' => 'required'
        ]);
        $image = $request->file('image');

        if(!is_null($image)){
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save(storage_path('/uploads/' . $filename));
            $data['image'] = $filename;
        }

        $data['date-entree']=Carbon::now();
        $lot = Lot::create($data);
        return response()->json($lot, 201);
    }
    /////////////////////////// Alerts /////////////////////////////////////
    public function getAlerts($id,$interval){
        $newTime = Carbon::now()->subMinutes($interval);
        $chambres = DB::table('chambres')
            ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
            ->join('streams', 'streams.carte_code', '=', 'cartes.code')
            ->join('alerts', 'streams.carte_code', '=', 'alerts.carte')
            ->where('alerts.created_at','>=' ,$newTime)
            ->when($id!=0, function ($query) use ($id) {
                return $query->where('chambres.id','=',$id);
            })
            ->select('chambres.id as chambre','type','message','alerts.created_at as date')
            ->orderByDesc('alerts.created_at')
            ->distinct()->get();
        return response()->json($chambres,201);        
    }

    ////////////////////////// Temp Hum //////////////////////////
    public function getSate($id,$typestat)
    {  
        $TypeStat=$typestat;
        $now = Carbon::now();
        if ($TypeStat==0){  // 0 statistiques du jour
        }
        elseif  ($TypeStat==1) // Statistique de la semaine en cours
        {
            DB::statement("SET lc_time_names = 'fr_FR'");
            $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
            $statistiques = DB::table('chambres')
                ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
                ->join('streams', 'streams.carte_code', '=', 'cartes.code')
                ->select(
                        DB::raw('dayname(streams.created_at) as created_at'), 
                        'chambres.id',
                        DB::raw('max(streams.temperature) as max_temp'), 
                        DB::raw('min(streams.temperature) as min_temp'),
                        DB::raw('max(streams.humedite) as max_hum'), 
                        DB::raw('min(streams.humedite) as min_hum')
                        )
                ->whereBetween('streams.created_at', [$weekStartDate, $weekEndDate])
                ->where('chambres.id','=',$id)
                ->groupBy(DB::raw('day(streams.created_at)'))
                ->get();
            return response()->json($statistiques,201);
        }
        elseif  ($TypeStat==2) // Statistique du mois en  par jour
        {
            DB::statement("SET lc_time_names = 'fr_FR'");
            $current_month = Carbon::now()->month;
            $statistiques = DB::table('chambres')
                ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
                ->join('streams', 'streams.carte_code', '=', 'cartes.code')
                ->select(
                    DB::raw('DAYOFMONTH(streams.created_at) as created_at'), 
                    'chambres.id',
                    DB::raw('max(streams.temperature) as max_temp'), 
                    DB::raw('min(streams.temperature) as min_temp'),
                    DB::raw('max(streams.humedite) as max_hum'), 
                    DB::raw('min(streams.humedite) as min_hum'),
                    
                )
                ->where(DB::raw('month(streams.created_at)'), '=', $current_month)
                //->where('chambres.id','=',$id)
                ->groupBy(DB::raw('day(streams.created_at)'))
                ->get();
                return response()->json($statistiques,201);
        }
        elseif  ($TypeStat==4) // Statistique du mois en cours par semaine
        {
            $current_month = Carbon::now()->month;
            $statistiques = DB::table('chambres')
                ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
                ->join('streams', 'streams.carte_code', '=', 'cartes.code')
                ->select(DB::raw('week(streams.created_at,1) as created_at'), 'chambres.id',
                DB::raw('max(streams.temperature) as max_temp'), 
                DB::raw('min(streams.temperature) as min_temp'),
                DB::raw('max(streams.humedite) as max_hum'), 
                DB::raw('min(streams.humedite) as min_hum')
                )
                ->where(DB::raw('month(streams.created_at)'), '=', $current_month)
                //->where('chambres.id','=',$id)
                ->groupBy(DB::raw('week(streams.created_at,1)'))
                ->get();
                return response()->json($statistiques,201);
        }
        elseif  ($TypeStat==3) // Statistique de l'annee en cours
        {
            DB::statement("SET lc_time_names = 'fr_FR'");
            $current_year = Carbon::now()->year;
            $statistiques = DB::table('chambres')
                ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
                ->join('streams', 'streams.carte_code', '=', 'cartes.code')
                ->select(DB::raw('MONTHNAME(streams.created_at) as created_at'), 
                'chambres.id',
                DB::raw('max(streams.temperature) as max_temp'), 
                DB::raw('min(streams.temperature) as min_temp'),
                DB::raw('max(streams.humedite) as max_hum'), 
                DB::raw('min(streams.humedite) as min_hum')
                )
                ->where(DB::raw('year(streams.created_at)'),'=',$current_year )
                // ->where('chambres.id','=',$id)
                ->groupBy(DB::raw('month(streams.created_at)'))
                ->get();
            return response()->json($statistiques,201);

        }
 
     }
}
