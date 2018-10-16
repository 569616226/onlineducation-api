<?php

namespace App\Http\Requests\Advert;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdvertRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth_user()->hasPermissionTo('update_advert');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'path' => 'required|string',
            'order' => 'required|integer',
            'type' =>[ 'required',Rule::in([0, 1])],
            'url' => 'required',
        ];
    }
}
