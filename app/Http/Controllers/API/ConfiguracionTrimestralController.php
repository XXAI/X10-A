<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ConfiguracionTrimestral;

class ConfiguracionTrimestralController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        try
        {        
            $parametros =Input::all();
            $arreglo_datos = [
                                'anio'=> $parametros['anio'],
                                'trimestre'=> $parametros['trimestre'],
                                'lote'=> $parametros['lote'],
                                'quincena'=> $parametros['quincena'],
                                'documento'=> $parametros['documento'],
                            ];
            $obj = ConfiguracionTrimestral::where("anio", "=", $parametros['anio'])->where("trimestre", "=", $parametros['trimestre'])->first();
            if($obj)
            {
                $obj->lote = $parametros['lote']; 
                $obj->quincena = $parametros['quincena']; 
                $obj->documento = $parametros['documento']; 
            }else{
                $obj = new ConfiguracionTrimestral();
                $obj->anio = $parametros['anio'];
                $obj->trimestre = $parametros['trimestre'];
                $obj->lote = $parametros['lote']; 
                $obj->quincena = $parametros['quincena']; 
                $obj->documento = $parametros['documento']; 
            }
            $obj->save();
            
            return response()->json(['mensaje'=>'Registrado Correctamente']);
            
        }catch (\Exception $e) {
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
        
    }
}
