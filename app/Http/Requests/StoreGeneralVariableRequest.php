<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeneralVariableRequest extends FormRequest
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
            'official_dollar'=>'required|numeric',
            'blue_dollar'=>'required|numeric|min:0.000001',
            'plus_m_discount'=>'sometimes|numeric|min:0.000001',
        ];
    }
}
