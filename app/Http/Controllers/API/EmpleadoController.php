<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;

use App\Models\Empleados;
class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       /*  $name = $request->get('buscar');
        //$name = 'VIDM870128TJA';
        $usuarios = Usuarios::with("horarios")->where('status', '=', 0);//->paginate(15);//->where("Badgenumber", "=", 921)->paginate(15);
        if($name !='')
            $usuarios = $usuarios->where("TITLE",'LIKE','%'.$name.'%');

        $usuarios = $usuarios->paginate(15);

        
        return response()->json(["usuarios" => $usuarios]); */
        //return view("reportes.kardex" , ['empleados' => $userinfo]);
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
        $empleados=new Empleados;        
        $empleados->nombre= $request->nombre;
        $empleados->apellido_paterno= $request->apaterno;
        $empleados->apellido_materno= $request->amaterno;
        $empleados->rfc= $request->rfc;
        $empleados->codigo_id= $request->codigo;
        $empleados->ur_id= $request->tipo;
        $empleados->cr_id= $request->tipo;
        $empleados->calculable= $request->tipo;
        $empleados->save();
        return response()->json($empleados);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
      //
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
