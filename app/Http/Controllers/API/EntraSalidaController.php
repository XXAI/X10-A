<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Omisiones;
use App\Models\incidenciasEliminadas;
use App\Models\ChecadasTrabajador;
use App\Models\Usuarios;
use App\Models\User;
use \Validator, \Hash, \Response;

use Carbon\Carbon, DB;

class EntraSalidaController extends Controller
{
    public function store(Request $request)
    {           
        try {
                
           
                $registro2 = new Omisiones;
                $registro2->USERID = $request->id;
                $registro2->CHECKTIME = $request->fing;
                $registro2->CHECKTYPE = $request->tipo_registro;
                $registro2->MODIFYBY = $request->idcap;     
                $registro2->DATE = now();
                $registro2->ISADD=  "1";
                $registro2->YUYIN=$request->razon;
                $registro2->INCOUNT=  "0";
                $registro2->ISMODIFY=  "0";
                $registro2->ISDELETE=  "0";
                $registro2->ISCOUNT=  "0";
                $registro2->save(); 

           
                 $registro = new ChecadasTrabajador;
                // $omision_id = Omisiones::latest('EXACTID')->first();
                $omision_id = Omisiones::max('EXACTID');
                $registro->USERID = $request->id;
                $registro->CHECKTIME = $request->fing;
                $registro->CHECKTYPE = $request->tipo_registro;
                $registro->VERIFYCODE = $request->idcap;        
                $registro->SENSORID = $request->idcap;                 
                $registro->MachineId =  "0"; 
                $registro->UserExtFmt =    $request->idcap;  
                $registro->WorkCode =   $omision_id;
                $registro->Memoinfo =$request->razon;
                $registro->sn=   $request->tipo_registro;
                $registro->save();

            //  dd($omision_id);

            
                
                return response()->json(['mensaje'=>'Registrado Correctamente']);
            } 
        catch (\Exception $e) {            
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
            }
    }


    public function destroy(Request $request,$id)
    {
        try {

            $datos = omisiones::where("EXACTID","=",$id)->first();
            // dd($datos);
              $datosinci = new incidenciasEliminadas;
              $datosinci->incidencia_id = $id;
              $datosinci->userid = $datos['USERID'];
              $datosinci->inicio = $datos['CHECKTIME'];
              $datosinci->captura_id = $request->idcap;
              $datosinci->fin = $datos['CHECKTIME'];
              $datosinci->user_id = $datos['MODIFYBY'];              
              $datosinci->motivo = $request->motivo;
              $datosinci->tipo_incidencia = $datos['CHECKTYPE'];
              $datosinci->fecha = now();
              $datosinci->save();    

        $registro = Omisiones::FindOrFail($id);   
       
        ChecadasTrabajador::where("WorkCode","=",$id)->delete();
        $result = $registro->delete();  

        if($result){
            return response()->json(['mensaje'=>'Registro Eliminado']);
            
        }
    } 
    catch (\Exception $e) {            
        return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
        }
    }
}
