<?php

namespace App\Http\Controllers;
use App\Models\Carte;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Http\Request\validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class CarteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('list', ['id' => '1']);
        $data = preg_replace("/&/", "\",\"", "Temp:23.00&Hum:78.00&Ec:2156.00&PH:5.60");
        $data = preg_replace("/:/", "\":\"", $data);
        $data="{\"".$data."\"}";
        $obj = json_decode($data,false);
        print $obj->{'Temp'}; // 12345

        $cartes = Carte::latest()->paginate(10);

        return view('list', compact('cartes'))
            ->with('i', (request()->input('page', 1) - 1) * 10);

        //return response()->json(Carte::all());
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Carte  $carte
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return response()->json(Carte::find($id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setdata (Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255',
            'data' => 'required|string|max:1024',
            'password' => 'required|string|max:1024',
        ]);

        if ($validator->fails()) {
            return  response()->json($validator->errors(), 401);
        }

        // get password form db
        $result= DB::table('cartes')
            ->select('password')
            ->where('code','=', $request->input('code'))
            ->get();

        if ($result->isEmpty()){
            return response()->json("Vérifier le code de la carte", 401);
        }

        $password=Crypt::decrypt($result[0]->password);
        $InputPassword=$request->input('password');
        // Comparre password

        if (strcmp($InputPassword, $password)!=0) {
            return response()->json($password, 401);
        }

        $stream = new Stream();
        $stream->carte_code = $request->input('code');
        $data= $this->getData($request->input('data'));
       $stream->temperature=$data->Temp;
        $stream->humedite=$data->Hum;
        $stream->etat_porte=$data->Porte;
        $stream->etat_compresseur=$data->Comp;
        $stream->etat_evaporateur=$data->Evap;

        $stream->save();
        return response()->json($stream, 200);
    }

    protected function create_carte(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);
        $carte = new Carte();
        $carte->password = Crypt::encrypt($request->input('password'));
        $carte->code= $request->input('code');
        $carte->save();
        return response()->json($carte, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Carte  $carte
     * @return \Illuminate\Http\Response
     */
    /*
    public function update(Request $request, $id)
    {
        $carte = Carte::findOrFail($id);
        $carte->update($request->all());

        return response()->json($carte, 200);
     }
    */
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carte  $carte
     * @return \Illuminate\Http\Response
     */
    /*
    public function destroy($id)
    {
        Carte::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }*/
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Carte  $carte
     * @return \Illuminate\Http\Response
     */
    /*
    public function edit(Carte $carte)
    {
        //return view('cartes.edit', compact('carte'));
    }
    */

    /**
     * Transform data to apprpiete format.
     *
     * @param  \App\Models\Sensor  $data , $key (Temp, Hum,EC, PH)
     * @return \Illuminate\Http\Response
     */
    public function TransformData($ListSensorData)
    {
        foreach ($ListSensorData as $elm){
            $data = preg_replace("/&/", "\",\"", $elm->data);
            $data = preg_replace("/:/", "\":\"", $data);
            $data="{\"".$data."\"}";
            $object = json_decode($data,false);
            $elm->data=$object;
        }
        return $ListSensorData;
    }

    public function getData($data)
    {
        $data = preg_replace("/&/", "\",\"", $data);
        $data = preg_replace("/:/", "\":\"", $data);
        $data="{\"".$data."\"}";
        $object = json_decode($data,false);

        return $object;
    }

}
