<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon, DB, PDF, Dompdf\Dompdf;

use App\Models\DiasOtorgados;
use App\Models\Omisiones;
use App\Models\User;
use App\Models\BaseUser;
use App\Models\ChecadasTrabajador;
use App\Models\Usuarios;
use App\Models\CluesUser;



class CapturistasExport implements FromCollection
{
    public function collection()
    {
        return DiasOtorgados::all();
    }
}