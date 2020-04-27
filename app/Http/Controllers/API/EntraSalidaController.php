<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Omisiones;
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
                /* $ultimo= ChecadasTrabajador::all();

                $ultimo_reg =var_dump($ultimo->last())+1; */
                $registro = new ChecadasTrabajador;
                $registro->USERID = $request->id;
                $registro->CHECKTIME = $request->fing;
                $registro->CHECKTYPE = $request->tipo_registro;
                $registro->VERIFYCODE = $request->idcap;        
                $registro->SENSORID = $request->idcap; 
               //$registro->LOGID=  "1";    
                $registro->MachineId=  "0"; 
                $registro->UserExtFmt=    "0";  
                $registro->WorkCode=  "0";
                $registro->Memoinfo=$request->razon;
                $registro->sn=   "0";
                $registro->save();

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
                return response()->json(['mensaje'=>'Registrado Correctamente']);
            } 
        catch (\Exception $e) {            
            return Response::json(['error' => $e->getMessage()], HttpResponse::HTTP_CONFLICT);
            }
    }
}
