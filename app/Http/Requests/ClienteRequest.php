<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'nombre'=>'required',
            //'matricula'=>'required',
            //'nombre_descuento'=>'required|in:descuento.id',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required'=>'El nombre del Cliente no puede estar vacio',
            //'matricula.required'=>'El NIT/CI no puede estar vacio',
            //'nombre_descuento.required'=>'Debe seleccionar un tipo de descuento',
        ];
    }
}
