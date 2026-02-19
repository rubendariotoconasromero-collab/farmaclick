<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'password'=>'required',
            'id_personal'=>'required',
            'id_grupo'=>'required'
        ];
    }
    public function messages()
    {
        return [
            'nombre.required'=>'El nombre no puede estar vacio',
            //'matricula.required'=>'El codigo no puede estar vacio',
            'password.required'=>'La contraseña no puede estar vacia',
            'id_personal.required'=>'Seleccione un Personal',
            'id_grupo.required'=>'Seleccione un Grupo',

        ];
    }
}
