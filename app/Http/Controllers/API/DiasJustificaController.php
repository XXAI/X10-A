<?php

namespace App\Http\Controllers\API;

use App\Models\DiasJustifica;
use App\Models\Incidencias;
use App\Models\incidenciasEliminadas;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\User;
use \Validator, \Hash, \Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB, PDF, View, Dompdf\Dompdf;
use Illuminate\Support\Facades\Input;

class DiasJustificaController extends Controller
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
        try {
                $registro = new DiasJustifica;
                $registro->USERID = $request->id;
                $registro->STARTSPECDAY = $request->fini;
                $registro->ENDSPECDAY = $request->ffin;
                $registro->DATEID = $request->tipo_incidencia;        
                $registro->YUANYING = $request->razon;
                $registro->DATE = now();
                $registro->captura_id=$request->idcap;
                $registro->incidencia_id = '';
                $registro->save();

                return response()->json(['data'=>$registro, 'mensaje'=>'Registrado Correctamente']);
            } 
        catch (\Exception $e) {
            
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DiasJustifica  $diasJustifica
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DiasJustifica  $diasJustifica
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
     * @param  \App\Models\DiasJustifica  $diasJustifica
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$incidencia_id)
    {
        $registro= Incidencias::findOrFail($incidencia_id);
        $registro->idvalida=$request->idcap;
        $registro->save(); 
        $reg2= DiasJustifica::findOrFail($incidencia_id);
        $reg2 ->captura_id=$request->idcap;
        $reg2->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DiasJustifica  $diasJustifica
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        //dd("holalalala: ".$id  );
        $datos = DiasJustifica::where("id","=",$id)->first();
      // dd($datos);
        $datosinci = new incidenciasEliminadas;
        $datosinci->incidencia_id = $id;
        $datosinci->userid = $datos['USERID'];
        $datosinci->inicio = $datos['STARTSPECDAY'];
        $datosinci->captura_id = $request->idcap;
        $datosinci->fin = $datos['ENDSPECDAY'];
        $datosinci->user_id = $datos['captura_id'];        
        $datosinci->motivo = $request->motivo;
        $datosinci->tipo_incidencia = $datos['DATEID'];
        $datosinci->fecha = now();
        $datosinci->save();

        $registro=DiasJustifica::FindOrFail($id);
        $result = $registro->delete();

        if($result){
            return response()->json(['mensaje'=>'Registro Eliminado']); 
            
        }
    }
}
