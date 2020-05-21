<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //|after_or_equal:fechaInicial
                'name' => 'required',
                'rf' => 'required',
                'codigo'    => 'required',
                'fechaing' => 'required|date',
                'codigo' => 'required',
                'clues' => 'required',
                'sexo' => 'required',
                'tipotra' => 'required',
                'area' => 'required',
                'code' => 'required',
                'fin_fec' => 'after_or_equal:ini_fec'

        ];
    }

    public function messages()
    {
        return [
            'rf.required'   => 'El campo RFC es obligatorio.',   
            'tipotra.required'   => 'El campo Tipo de Trabjador es obligatorio.',
            'fechaing.required'   => 'La fecha de ingreso es obligatorio.',  
            'code.required'   => 'El Horario es obligatorio.',
        ];
    }
}
