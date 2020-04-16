<?php

namespace App\Http\Controllers\API;

use App\Models\TiposIncidencia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TipoIncidenciaController extends Controller
{
    public function llenarSelect()
    {
        $incidencias = TiposIncidencia::all();         
        return \View::make('convocatoria.parteprueba',compact('incidencias'));
    }
}
