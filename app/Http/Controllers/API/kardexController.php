<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon, DB;

class kardexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('name');
        if(isset($name) || $name<>""){
            $userinfo= DB::TABLE("user_of_run")
            ->join("checkinout","user_of_run.userid","=","checkinout.userid")
            ->leftjoin("userinfo","userinfo.userid","=","user_of_run.userid")
            ->groupBy("userinfo.USERID","userinfo.Badgenumber","userinfo.Name","userinfo.TITLE",
            "userinfo.PAGER","userinfo.street","userinfo.MINZU","userinfo.DEFAULTDEPTID")
            ->select("userinfo.USERID","userinfo.Badgenumber","userinfo.Name","userinfo.TITLE",
            "userinfo.PAGER as Codigo","userinfo.street as TipoTrabajador","userinfo.MINZU as Area","userinfo.DEFAULTDEPTID")
            ->orderBy("userinfo.userid")
            ->where("userinfo.Badgenumber","=",$name)
            ->orWhere("userinfo.TITLE","=",$name)
            ->orWhere("userinfo.Name","like","%$name%")                      
            ->paginate(15);
        }

        else
        {
            $userinfo= DB::TABLE("user_of_run")
            ->join("checkinout","user_of_run.userid","=","checkinout.userid")
            ->leftjoin("userinfo","userinfo.userid","=","user_of_run.userid")
            ->groupBy("userinfo.USERID","userinfo.Badgenumber","userinfo.Name","userinfo.TITLE",
            "userinfo.PAGER","userinfo.street","userinfo.MINZU","userinfo.DEFAULTDEPTID")
            ->select("userinfo.USERID","userinfo.Badgenumber","userinfo.Name","userinfo.TITLE",
            "userinfo.PAGER as Codigo","userinfo.street as TipoTrabajador","userinfo.MINZU as Area","userinfo.DEFAULTDEPTID")
            ->orderBy("userinfo.userid")                                
            ->paginate(15);
        }
       
        return view("reportes.kardex" , ['empleados' => $userinfo]);
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
