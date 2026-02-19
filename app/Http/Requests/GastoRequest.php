<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GastoRequest extends FormRequest
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
            'monto'=>'required',
            'id_motivo_gasto'=>'required'
        ];
    }
    
    public function messages()
    {
        return [
            'monto.required'=>'El monto es Requerido',
            'id_motivo_gasto.required'=>'Seleccione motivo de gasto',
        ];
    }
}
