<?php

namespace App\Http\Controllers\API;

use App\Models\DiasJustifica;
use Illuminate\Http\Request;
use App\Models\Usuarios;

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
        $registro = new DiasJustifica;
        $registro->nombre = $request->nombre;
        $registro->especialidad = $request->especialidad;
        $registro->cedula = $request->cedula;
        $registro->telefono = $request->telefono;
        $registro->email = $request->email;
        $registro->save();
        return response()->json(['mensaje'=>'Registrado Correctamente']);
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
