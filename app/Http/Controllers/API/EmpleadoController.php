<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;

use App\Models\Usuarios;
use App\Models\UsuarioHorario;
use App\Models\TiposIncidencia;
class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('buscar');
       
        $usuarios = Usuarios::with("horarios.detalleHorario")->where('status', '=', 0);//->paginate(15);//->where("Badgenumber", "=", 921)->paginate(15);
        if($name !='')
            $usuarios = $usuarios->where("TITLE",'LIKE','%'.$name.'%')
                    ->orWhere("Name",'LIKE','%'.$name.'%')
                    ->orWhere("Badgenumber",'=',$name);

        $usuarios = $usuarios->paginate(15);
        $incidencias = TiposIncidencia::orderBy('LeaveName','ASC')->get();  
        
        
        return response()->json(["usuarios" => $usuarios,"incidencias" => $incidencias]);
       
    }


    public function llenarSelect()
    {
        $incidencias = TiposIncidencia::all();         
        return \View::make('incidenciaform',compact('incidencias'));
        //return $incidencias;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return "glltrltrltlrk";
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
}
