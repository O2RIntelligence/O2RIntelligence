<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGeneralVariableRequest extends FormRequest
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
            'id'=>'required|uuid',
            'official_dollar'=>'sometimes|numeric',
            'blue_dollar'=>'sometimes|numeric',
            'plus_m_discount'=>'sometimes|numeric',
        ];
    }
}
