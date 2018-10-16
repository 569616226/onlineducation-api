<?php

namespace App\Http\Requests\Educational;

use Illuminate\Foundation\Http\FormRequest;

class CreateEducationalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth_user()->hasPermissionTo('create_educational');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required|string',
            'content' => 'required|string',
        ];
    }
}
