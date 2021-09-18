<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ConfiguracionTrimestral;
use Illuminate\Support\Facades\Input;
use \Validator, \Hash, \Response;
use Illuminate\Support\Facades\Auth;

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
        $parametros =Input::all();
        try
        {      
            $parametros =Input::all();
           $obj = ConfiguracionTrimestral::where("anio", "=", $parametros['config_anio'])->where("trimestre", "=", $parametros['config_trimestre'])->first();
            if($obj)
            {
                $obj->lote = $parametros['config_lote']; 
                $obj->quincena = $parametros['config_quincena']; 
                $obj->no_documento = $parametros['config_documento']; 
                $obj->tipo_trabajador = $parametros['config_tipo_trabajador']; 
            }else{
                $obj = new ConfiguracionTrimestral();
                $obj->anio = $parametros['config_anio'];
                $obj->trimestre = $parametros['config_trimestre'];
                $obj->lote = $parametros['config_lote']; 
                $obj->quincena = $parametros['config_quincena']; 
                $obj->no_documento = $parametros['config_documento']; 
                $obj->tipo_trabajador = $parametros['config_tipo_trabajador'];
                $obj->user_id = Auth::id();
            }
            $obj->save();
            
            return response()->json(['mensaje'=>'Registrado Correctamente']);
            
        }catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DiasJustifica  $diasJustifica
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
         try
        {        
            $parametros =Input::all();
           
            $obj = ConfiguracionTrimestral::where("anio", "=",$parametros['anio'])
                                        ->where("trimestre", "=", $parametros['trimestre'])
                                        ->where("tipo_trabajador", "=", $parametros['tipo_trabajador'])
                                        ->first();
            return response()->json(['data'=>$obj]);
        }catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        } 
    }

   
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
