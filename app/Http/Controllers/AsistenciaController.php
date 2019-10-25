<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      // dd($request -> get('trip-start'));
       //dd($request -> get('trip-fin'));
       /*$asistencia = DB::table('CHECKINOUT')
            ->join('USERINFO', 'CHECKINOUT.userid', '=', 'USERINFO.userid')

            ->select('USERINFO.Name', 'CHECKINOUT.USERID',DB::raw('date(CHECKINOUT.CHECKTIME) as fecha'),
                'USERINFO.Badgenumber','USERINFO.TITLE',DB::raw('right(CHECKINOUT.CHECKTIME,8) as csalida'))
            ->where('USERINFO.Badgenumber', '=' , $request -> get('id'))
            ->where('USERINFO.TITLE', '=' , $request -> get('rfc'))
            ->where('CHECKINOUT.CHECKTIME','>=','20191001')
           ->whereBetween('CHECKINOUT.CHECKTIME', [$request -> get('trip-start'),$request -> get('trip-fin')])
           // ->groupBy('fecha','CHECKINOUT.userid')

            ->get();*/
         $empleado = DB::TABLE("userinfo")
                            ->join("user_of_run", "userinfo.USERID", "=", "user_of_run.USERID")
                            ->join("num_run", "num_run.NUM_RUNID", "=", "user_of_run.NUM_OF_RUN_ID")
                            ->join("num_run_deil", "num_run_deil.NUM_RUNID", "=", "num_run.NUM_RUNID")
                            ->join("schclass", "schclass.schClassid", "=", "num_run_deil.SCHCLASSID")
                            ->select("userinfo.name"
                                    ,"num_run.name as horario"
                                    ,DB::RAW("SUBSTRING(num_run.STARTDATE, 1, 10) as fecha_inicial")
                                    ,DB::RAW("SUBSTRING(num_run.ENDDATE, 1, 10) as fecha_final")
                                    ,"num_run_deil.SDAYS as dia"
                                    ,"schclass.schName as Detalle_Horario"
                                    ,DB::RAW("SUBSTRING(schclass.StartTime, 12, 5) as HoraInicio")
                                    ,"schclass.EndTime as HoraFin"
                                    ,"schclass.LateMinutes as Tolerancia"
                                    ,DB::RAW("SUBSTRING(schclass.CheckInTime1, 12, 5) as InicioChecarEntrada")
                                    ,DB::RAW("SUBSTRING(schclass.CheckInTime2, 12, 5) as FinChecarEntrada")
                                    ,DB::RAW("SUBSTRING(schclass.CheckOutTime1, 12, 5) as InicioChecarSalida")
                                    ,DB::RAW("SUBSTRING(schclass.CheckOutTime2, 12, 5) as FinChecarSalida")
                                    )
                            ->where("userinfo.USERID", "=",  $request -> get('id'))->get();











        return view('home',['asistencia' => $asistencia]);
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
