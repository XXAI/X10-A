<?php

namespace App\Http\Controllers\API;

use App\Models\Incidencias;
use App\Models\DiasJustifica;
use App\Models\DiasOtorgados;
use App\Models\Usuarios;
use App\Models\ReglasHorarios;
use App\Models\Festivos;
use App\Models\SalidaAutorizada;
use App\Models\Departamentos;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;

class IncidenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         
       
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
            $registro = new Incidencias;
            $registro->USERID = $request->id;
            $registro->fecha_ini = $request->fini;
            $registro->fecha_fin = $request->ffin;
            $registro->incidencias_tipo_id = $request->tipo_incidencia;        
            $registro->documentos = $request->documentos;
            $registro->observaciones = $request->observaciones;
            $registro->autoriza = $request->autorizo;            
            $registro->idvalida=0;
            $registro->save();

           /* 
            $speday = new DiasJustifica;
            $speday->USERID = $request->id;
            $speday->STARTSPECDAY = $request->fini;
            $speday->ENDSPECDAY = $request->ffin;
            $speday->DATEID = $request->tipo_incidencia;        
            $speday->YUANYING = $request->documentos;
            $speday->DATE = now();
            $speday->captura_id=0;
            $speday->incidencia_id = $registro->id;
            $speday->save(); */
                   
            return response()->json(['id_inci'=>$registro->id]);
        } 
    catch (\Exception $e) {
        
        return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $incidencias_empleado = Incidencias::with("TiposIncidencia")->find($id);  
    
        return response()->json(["data" => $incidencias_empleado]);
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
        $registro=Incidencias::FindOrFail($id);       
        DiasJustifica::where('incidencia_id','=',$id)->delete();
        $result = $registro->delete();

        if($result){
            return response()->json(['mensaje'=>'Registro Eliminado']);
            
        }
    }
}
