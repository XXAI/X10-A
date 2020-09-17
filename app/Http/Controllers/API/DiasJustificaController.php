<?php

namespace App\Http\Controllers\API;

use App\Models\DiasJustifica;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use App\Models\User;
use \Validator, \Hash, \Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;

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
                $registro->incidencia_id = $request->id_inci;
                $registro->save();
                return response()->json(['mensaje'=>'Registrado Correctamente']);
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
    public function update(Request $request,$id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DiasJustifica  $diasJustifica
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $registro=DiasJustifica::FindOrFail($id);
        $result = $registro->delete();

        if($result){
            return response()->json(['mensaje'=>'Registro Eliminado']);
            
        }
    }
}
