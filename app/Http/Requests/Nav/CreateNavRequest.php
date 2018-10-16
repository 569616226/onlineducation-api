<?php

namespace App\Http\Requests\Nav;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateNavRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth_user()->hasPermissionTo('create_nav');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'required',
            'order_type' => [ 'required',Rule::in([1, 2])],
            'pictrue'    => 'required|url',
            'is_hide'    => 'required|boolean',
            'type' =>[ 'required',Rule::in([0, 1])],
        ];
    }
}
