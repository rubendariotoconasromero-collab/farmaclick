<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticuloRequest extends FormRequest
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
            //'nombre_comercial'=>'required',
            //'nombre_generico'=>'required',
            //'id_categoria'=>'required',
            'id_unidad'=>'required',
            'id_proveedor'=>'required',
            //'id_marca'=>'required',

        ];
    }
    public function messages()
    {
        return [
            //'nombre_comercial.required'=>'El nombre comercial del Producto no puede estar vacio',
            //'nombre_generico.required'=>'El nombre generico del Producto no puede estar vacio',
            //'id_categoria.required'=>'Seleccione una Categoria',
            'id_unidad.required'=>'Seleccione una Presentación',
            'id_proveedor.required'=>'Seleccione un Laboratorio',
            //'id_marca.required'=>'Seleccione un Linea',

        ];
    }
}
