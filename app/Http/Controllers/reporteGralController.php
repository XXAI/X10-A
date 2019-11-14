<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Carbon\Carbon, DB;

class reporteGralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arreglo_fecha = array();
        $fecha_actual = Carbon::now();
        $anio_actual = $fecha_actual->year;
        $mes_actual = $fecha_actual->month;
        $dia_actual = $fecha_actual->day;
        //
        $inicio = "";
        $fin =  "";

        //$Rfc = str_replace("(", "/", $Rfc);
       // $desc = $this->decrypt($Rfc);

        $fecha_view_inicio = Carbon::now()->startOfMonth();
        $fecha_view_fin    = Carbon::now();

        if($inicio == null){
            $f_ini = Carbon::now()->startOfMonth();
            $f_fin = Carbon::now()->addDays(1);
            $ff_fin = Carbon::now();
            $inicio = date("Y-m-")."01";
            $fin = date("Y-m-d");
        }else{
            $f_ini= new Carbon($inicio);
            $f_fin = new Carbon($fin);
            $ff_fin = new Carbon($fin);
            $f_fin = $f_fin->addDays(1);

            $fecha_view_inicio = new Carbon($inicio);
            $fecha_view_fin    = new Carbon($fin);
        }
        
     
        $checa_dias = DB::table("user_speday")
                ->join("USERINFO", "USERINFO.USERID", "=", "user_speday.USERID")
                ->join("leaveclass","leaveclass.LeaveId", "=", "user_speday.DATEID")                         
               
                ->whereBetween(DB::RAW("DATEPART(DW,STARTSPECDAY)"),[2,6])
                ->groupBy('leaveclass.LeaveId','leaveclass.LeaveName',"user_speday.userid")           
                ->select("user_speday.userid","leaveclass.LeaveName as Exepcion"                            
                ,'leaveclass.LeaveId AS TIPO'
                ,DB::RAW("count(leaveclass.LeaveId) as total")                               
                )           
                ->get();
           
            $vac19_1=0;
            $vac19_2=0;
            $vac18_1=0;
            $vac18_2=0;
            $diaE=0;
            $vacEx=0;
            $vacMR=0;
            $diaE=0;
            $ono=0;



            $buscaHorario=DB::table("USER_OF_RUN")                  
            //->where("USERID", "=",  $validacion->USERID)                                 
            ->where("STARTDATE","<=",substr($ff_fin, 0, 10).'T23:59:59.000')
            ->where("ENDDATE",">=",substr($f_ini, 0, 10).'T00:00:01.000')   
            ->orderBy("ENDDATE")   
            ->select("USERID",
                    "NUM_OF_RUN_ID",
                    DB::RAW("CONVERT(nvarchar(10), STARTDATE,120) as STARTDATE"),
                    DB::RAW("CONVERT(nvarchar(10), ENDDATE,120) as ENDDATE"),
                    "ORDER_RUN")                       
            ->groupBy("userid","NUM_OF_RUN_ID","STARTDATE", "ENDDATE","ORDER_RUN")          
            ->get();
            $arreglo_reglas=array();  
                    $ind=0;
                   // foreach($buscaHorario as $key => $horario){                        
                        $empleado = DB::TABLE("user_of_run")                            
                        ->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID")
                        ->join("num_run_deil", "num_run_deil.NUM_RUNID", "=", "num_run.NUM_RUNID")
                        ->join("schclass", "schclass.schClassid", "=", "num_run_deil.SCHCLASSID")
                        ->select("num_run.name as horario"
                                ,DB::RAW("CONVERT(nvarchar(10),user_of_run.STARTDATE,120) as fecha_inicial")
                                ,DB::RAW("CONVERT(nvarchar(10),user_of_run.ENDDATE,120) as fecha_final")
                                ,"num_run_deil.SDAYS as dia"
                                ,"schclass.schName as Detalle_Horario"
                                ,DB::RAW("CONVERT(nvarchar(5), schclass.StartTime,108) as HoraInicio")
                                ,DB::RAW("CONVERT(nvarchar(5), schclass.EndTime,108) as HoraFin")
                                ,"schclass.LateMinutes as Tolerancia"
                                ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime1, 108) as InicioChecarEntrada")
                                ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckInTime2, 108) as FinChecarEntrada")
                                ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime1, 108) as InicioChecarSalida")
                                ,DB::RAW("CONVERT(nvarchar(5), schclass.CheckOutTime2, 108) as FinChecarSalida")
                                ,"schclass.CheckOutTime2 as prueba"
                                ,DB::RAW("left(schclass.schClassId, 2) as idH")                                 )                           
                                
                                //->where("USER_OF_RUN.NUM_OF_RUN_ID","=",$horario->NUM_OF_RUN_ID)
                                ->groupBy("user_of_run.userid","num_run.name","user_of_run.STARTDATE", "user_of_run.ENDDATE","num_run_deil.SDAYS","schclass.schName","schclass.StartTime","schclass.EndTime","schclass.LateMinutes","schclass.CheckInTime1","schclass.CheckInTime2"
                                ,"schclass.CheckOutTime1","schclass.CheckOutTime2","schclass.schClassId")          
                                ->get();

                            $ind = count($arreglo_reglas);
                            $arreglo_reglas[$ind]['horario'] = $buscaHorario[$key];
                            $arreglo_reglas[$ind]['dias'] = $empleado; 
                                                 
                           
                  //  }         

            return $empleado;

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
    public function show($id)
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
