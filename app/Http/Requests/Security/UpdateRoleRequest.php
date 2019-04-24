<?php

namespace App\Http\Requests\Security;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
            'name'                      => 'required|min:4|max:32',
            'sort'                      => 'required|numeric',
            'associated-permissions'    => 'required',
            'permissions'               => 'sometimes|required'
        ];
    }
}
