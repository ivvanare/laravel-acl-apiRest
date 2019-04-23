<?php

namespace App\Http\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserRequest extends FormRequest
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
            'name'                  => 'required|min:4|max:32',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:8|max:32|confirmed',
            'remember_me'           => 'required|boolean'
            //'assignees_roles'       => 'required',

        ];
    }
}
