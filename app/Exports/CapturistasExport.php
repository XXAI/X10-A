<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon, DB, PDF, Dompdf\Dompdf;

use App\Models\DiasOtorgados;
use App\Models\Omisiones;
use App\Models\User;
use App\Models\BaseUser;
use App\Models\ChecadasTrabajador;
use App\Models\Usuarios;
use App\Models\CluesUser;






class CapturistasExport implements FromView
{
    public function view(): View
    {

        $parametros = Input::all();
        $inicio = $request->inicio;
        $fin = $request->fin;
        $user = $request->get('user');
    
        $logs = DiasOtorgados::with("siglas","capturista","empleado")->whereNotNull("captura_id")->whereBetween("DATE",[$inicio,$fin]);
        
        

        if($user != 'NaN') {          
          $logs=$logs->Where(function($query)use($user){
          $query->where("captura_id",'=',$user);
          }); 
         }
         return array('logs'=>$logs);
        
       
    }
}