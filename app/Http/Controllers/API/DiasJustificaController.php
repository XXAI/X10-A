<?php

namespace App\Http\Controllers\API;

use App\Models\DiasJustifica;
use Illuminate\Http\Request;
use App\Models\Usuarios;
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
        
        /* $mensajes = [
            'required'           => "required",
        ];

        $reglas = [
            'DATEID'            => 'required',
            'YUANYING'             => 'required',            
        ];     
      

        $inputs = Input::all();
       
        
        $v = Validator::make($inputs, $reglas, $mensajes);

        if ($v->fails()) {
            return response()->json(['error' => "No se encuentra el recurso que esta buscando."], HttpResponse::HTTP_NOT_FOUND);
        }
        */
       
       
        try {
                $registro = new DiasJustifica;
                $registro->USERID = $request->id;
                $registro->STARTSPECDAY = $request->fini;
                $registro->ENDSPECDAY = $request->ffin;
                $registro->DATEID = $request->tipo_incidencia;        
                $registro->YUANYING = $request->razon;
                $registro->DATE = now();
                $registro->save();
                return response()->json(['mensaje'=>'Registrado Correctamente']);
        } catch (\Exception $e) {
            
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
        //
    }
}
