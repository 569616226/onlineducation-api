<?php

namespace App\Http\Requests\Train;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth_user()->hasPermissionTo('update_train');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            'title'       => 'required',
            'pic'         => 'required',
            'start_at'    => 'required',
            'end_at'      => 'required',
            'address'     => 'required',
            'discrible'   => 'required',
            'nav_id'      => 'required|exists:navs,id',
            'geren_ids.*' => 'required|exists:genres,id',
        ];
    }
}
