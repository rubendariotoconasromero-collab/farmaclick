<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $ignoreUserId = $this->isMethod('put') ? $this->input('id') : null;

        return [
            'id' => $this->isMethod('put') ? ['required', 'integer', 'exists:users,id'] : ['nullable'],
            'nombre' => [
                'required', 'string', 'max:255',
                Rule::unique('users', 'name')->ignore($ignoreUserId),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'password' => $this->isMethod('post')
                ? ['required', 'string', 'min:8']
                : ['nullable', 'string', 'min:8'],
            'id_personal' => ['required', 'integer', 'exists:personal,id'],
            'id_grupo' => [
                'required',
                'integer',
                Rule::exists('grupo', 'id')->where(fn ($query) => $query
                    ->where('estado', 1)
                    ->where('is_super_admin', false)),
            ],
            'estado' => ['required', Rule::in([0, 1, '0', '1'])],
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre no puede estar vacío.',
            'nombre.unique' => 'El nombre de usuario ya está registrado.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'password.required' => 'La contraseña no puede estar vacía.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'id_personal.required' => 'Seleccione un personal.',
            'id_personal.exists' => 'El personal seleccionado no existe.',
            'id_grupo.required' => 'Seleccione un grupo.',
            'id_grupo.exists' => 'El grupo seleccionado no está disponible.',
        ];
    }
}
