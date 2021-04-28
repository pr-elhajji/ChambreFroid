<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Chambre;
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
            ->join('lots', 'lots.chambre_id', '=', 'chambres.id')
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(chambre $request)
    {
        //TODO à verifier le validation
        /* $validator = Validator::make($request->all(), [
                  'marque' => 'required|string|max:255',
                                    'serie' => 'required|string|max:255',
                                        'model' => 'required|string|max:255',
                                        'format' => 'required|string|max:255',
                                        'cnv' => 'string|max:255',
                                        'date_fabrication' => 'string|max:255',
                                        'compresseur' => 'string|max:255',
                                        'local' => 'string|max:255',
                                        'image' => 'string|max:255',
                                        'temperature' => 'string|max:255',
                                        'date_installation' => 'string|max:255',
      ]);
      if ($validator->fails()) {
                     return  response()->json($validator->errors(), 401);
                 }*/
        $chambre = $request;
        $chambre->save();
        return response()->json($chambre, 201);
    }



    /**
     * retourner la liste des chambres par client
     *
     * @return \Illuminate\Http\Response
     */
    public function RendementStat($id,$typestat)
    {
        /* $validator = Validator::make($request->all(), [
             'id' => 'required|int',
             'typestat' => 'required|int',
         ]);
         if ($validator->fails()) {
             return  response()->json($validator->errors(), 401);
         }
         $idchambre=$request->input('id');
         $TypeStat=$request->input('typestat');*/
        $idchambre=$id;
        $TypeStat=$typestat;
        $now = Carbon::now();
        if ($TypeStat==0){  // 0 statistiques du jour



        }elseif  ($TypeStat==1) // Statistique de la semaine en cours
        {
            DB::statement("SET lc_time_names = 'fr_FR'");
            $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
            $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
            $statistiques = DB::table('chambres')
                ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
                ->join('streams', 'streams.carte_code', '=', 'cartes.code')
                ->select(DB::raw('dayname(streams.created_at) as jour'), DB::raw('sum(streams.count) as rendement'))
                ->whereBetween('streams.created_at', [$weekStartDate, $weekEndDate])
                ->where('chambres.id','=', $idchambre)
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
                ->select(DB::raw('DAYOFMONTH(streams.created_at) as jour'), DB::raw('sum(streams.count) as rendement'))
                ->where(DB::raw('month(streams.created_at)'), '=', $current_month)
                ->where('chambres.id','=', $idchambre)
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
                ->select(DB::raw('week(streams.created_at,1) as semaine'), DB::raw('sum(streams.count) as rendement'))
                ->where(DB::raw('month(streams.created_at)'), '=', $current_month)
                ->where('chambres.id','=', $idchambre)
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
                ->select(DB::raw('MONTHNAME(streams.created_at) as mois'), DB::raw('sum(streams.count) as rendement'))
                ->where(DB::raw('year(streams.created_at)'),'=',$current_year )
                ->where('chambres.id','=', $idchambre)
                ->groupBy(DB::raw('month(streams.created_at)'))
                ->get();


            return response()->json($statistiques,201);

        }

    }

    public function getCapacite($id)
    {
        $chambres = DB::table('chambres')
            ->join('lots', 'lots.chambre_id', '=', 'chambres.id')
            ->select( 'chambres.numero','chambres.capacite',DB::raw('100*sum(lots.capacite)/chambres.capacite as taux'), DB::raw('sum(lots.capacite) as capcite_reel'))
            ->where('chambres.id','=' ,$id)
            ->get();

        return response()->json($chambres,201);
    }

    public function getTempHum($id,$interval)
    {
        $newTime = Carbon::now()->subMinutes($interval);
        $chambres = DB::table('chambres')
            ->join('cartes', 'cartes.chambre_id', '=', 'chambres.id')
            ->join('streams', 'streams.carte_code', '=', 'cartes.code')
            ->select( 'temperature', 'humedite','streams.created_at')
            ->where('streams.created_at','>=' ,$newTime)
            ->orderByDesc('streams.created_at')
            ->get();
        return response()->json($chambres,201);
    }
    public function getLots($id)
    {
        $chambres = DB::table('chambres')
            ->join('lots', 'lots.chambre_id', '=', 'chambres.id')
            ->join('varietes', 'lots.variete_id', '=', 'varietes.id')
            ->select( 'chambres.id', 'lots.*','varietes.libelle')
            ->where('chambres.id','=' ,'1')
            ->get();

        return response()->json($chambres,201);
    }

    // ajouter un lot à chambre
    public function addLots(){

    }
}
