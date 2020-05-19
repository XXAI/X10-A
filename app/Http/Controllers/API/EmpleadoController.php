<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\EmpleadoRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;
use App\Models\Usuarios;
use App\Models\Departamentos;
use App\Models\UsuarioHorario;
use App\Models\Horario;
use App\Models\TiposIncidencia;


use App\Models\User;
//use \Hash, \Response;
//use Illuminate\Support\Facades\Validator;

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
        
        $usuarios = Usuarios::with("horarios.detalleHorario")->where('status', '=', 0);
        if($name !='')
            $usuarios = $usuarios->where("TITLE",'LIKE','%'.$name.'%')
                    ->orWhere("Name",'LIKE','%'.$name.'%')
                    ->orWhere("Badgenumber",'=',$name);

        $usuarios = $usuarios->orderBy('USERID','DESC')->paginate(15);
        $incidencias = TiposIncidencia::orderBy('LeaveName','ASC')->whereNotIn('LeaveId', [4,5,7,9,18,28])->get();  
        $departamentos = Departamentos::where("DEPTID","<>",1)->get();     
        
        
        return response()->json(["usuarios" => $usuarios,"incidencias" => $incidencias,"departamentos" => $departamentos]);
       
    }

    public function fetch(Request $request)
    {
        
        if($request->get('bh'))
        {  
        $bh = $request->get('bh');
        $data = Horario::select('NUM_RUNID','NAME')->where("NAME",'LIKE','%'.$bh.'%')->get();        
        
        $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
      foreach($data as $row)
      {
       $output .= '
       <li><a href="#">'.$row->NAME.'</a></li>
       ';
      }
      $output .= '</ul>';
      return  $output;
     // return response()->json($data);  
        
        }
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
    public function store(EmpleadoRequest $request)
    {

   
       // if($request->ajax()){
       
           
                $max=Usuarios::max('USERID');
                $maxid=Usuarios::select('Badgenumber as num_max')
                
                ->where('USERID','=',$max)
                ->get();         
                $maxid=($maxid[0]->num_max)+1;  
                    $registro = new Usuarios;
                    $registro->Badgenumber= $maxid;
                    $registro->Name = $request->name;
                    $registro->Gender = $request->sexo;
                    $registro->TITLE = $request->rf;        
                    $registro->PAGER = $request->codigo;
                    $registro->BIRTHDAY = $request->fecnac;
                    $registro->HIREDDAY=$request->fechaing;
                    $registro->street=$request->street;
                    $registro->CITY=$request->city;
                    $registro->STATE=0;
                    $registro->ZIP= 1;
                    $registro->FPHONE=$request->clues;
                    $registro->DEFAULTDEPTID=$request->tipotra;            
                    $registro->MINZU=$request->area;        

                    $registro->save();
                    return response()->json(['mensaje'=>'Registrado Correctamente ID:  '. $maxid]); 
       // }
           
        
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
       
    }
}
