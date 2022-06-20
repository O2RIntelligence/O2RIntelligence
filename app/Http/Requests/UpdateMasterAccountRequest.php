<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterAccountRequest extends FormRequest
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
            'name'=>'sometimes|string|max:255',
            'account_id'=>'sometimes|numeric',
            'developer_token'=>'sometimes|string|max:255',
            'discount'=>'sometimes|numeric',
            'revenue_conversion_rate'=>'sometimes|numeric',
        ];
    }
}
