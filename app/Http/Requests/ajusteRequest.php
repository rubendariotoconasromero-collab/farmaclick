<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjusteRequest extends FormRequest
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
            'costo_mayorista'=>'required',
            'costo_unitario'=>'required',
            'costo_preferencial'=>'required',
            'costo_compra'=>'required',
        ];
    }
    public function messages()
    {
        return [
            'costo_mayorista.required'=>'El Precio Mayorista no puede estar vacio',
            'costo_unitario.required'=>'El Precio Unitario no puede estar vacio',
            'costo_preferencial.required'=>'El Precio Preferencial no puede estar vacio',
            'costo_compra.required'=>'El Costo de Compra no puede estar vacio',

        ];
    }
}
