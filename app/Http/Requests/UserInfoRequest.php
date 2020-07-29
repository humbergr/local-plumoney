<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class UserInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function __construct()
    {
        App::setLocale('es');
    }
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
            'primer_nombre'       => 'required|max:191',
            'segundo_nombre'      => 'required_unless:check_segundo_nombre,true|max:191',
            'primer_apellido'     => 'required|max:191',
            'segundo_apellido'    => 'required_unless:check_segundo_apellido,true|max:191',
            'fecha_de_nacimiento' => 'required|date|max:191',
            'correo_electronico'  => 'nullable|email|max:191',
            'local'               => 'nullable|max:191',
            'pais'                => 'required|max:191',
            'estado_departamento' => 'required|max:191',
            'ciudad'              => 'required|max:191',
            'codigo_postal'       => 'required|max:191',
            'direccion'           => 'required|max:191',
            'tipo'                => 'required|max:191',
            'piso'                => 'required|max:191',
            'habitacion'          => 'required|max:191',
            'selfie'              => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4000',
            // 'UserPersonProfile[pre-local]' => 'required|max:191',
            //'body'                           => 'required',
        ];
    }

    public function attributes()
    {
        return [
            '__selfie_input' => 'Foto Selfie',

        ];
    }

    public function messages()
    {
        return [
            'segundo_nombre.required_unless'   => 'El segundo nombre es obligatorio al menos que no posea en su documento de identidad',
            'segundo_apellido.required_unless' => 'El segundo apellido es obligatorio al menos que no posea en su documento de identidad',
        ];
    }
}
