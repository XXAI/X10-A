<?php

namespace App\Http\Controllers;

$carbon = new \Carbon\Carbon();
$date = $carbon->now();
use Illuminate\Http\Request;
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
                  $asistencia = DB::table('CHECKINOUT')
            ->join('USERINFO', 'CHECKINOUT.userid', '=', 'USERINFO.userid')
            ->join('user_of_run', 'user_of_run.userid', '=','CHECKINOUT.userid')
            ->join('num_run_deil','num_run_deil.NUM_RUNID', '=', 'user_of_run.NUM_OF_RUN_ID')
            ->select('USERINFO.Name', 'CHECKINOUT.USERID',DB::raw('date(CHECKINOUT.CHECKTIME) as fecha'),
                'USERINFO.Badgenumber','USERINFO.TITLE',DB::raw('right(min(CHECKINOUT.CHECKTIME),8) as centrada'),

                DB::raw('right(max(CHECKINOUT.CHECKTIME),8) as csalida'),
                DB::raw('right(num_run_deil.STARTTIME,8) as hentrada'),DB::raw('right(num_run_deil.ENDTIME,8) as hsalida'),
                DB::raw('TIMEDIFF(right(min(CHECKINOUT.CHECKTIME),8),right(num_run_deil.STARTTIME,8)) as difent'),
                DB::raw('TIMESTAMPDIFF(MINUTE,
                    concat(date(CHECKINOUT.CHECKTIME)," ", right(num_run_deil.ENDTIME,8)),
                    max(CHECKINOUT.CHECKTIME)
                    )
                    as difsal')







            )

            ->where('USERINFO.Badgenumber', '=' , $request -> get('id'))
            ->where('USERINFO.TITLE', '=' , $request -> get('rfc'))
            ->where('CHECKINOUT.CHECKTIME','>=','20191001')
           ->whereBetween('CHECKINOUT.CHECKTIME', [$request -> get('trip-start'),$request -> get('trip-fin')])
           ->groupBy('fecha','USERINFO.userid')


            ->get();



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
