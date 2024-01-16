<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class Payment_methodRequest extends FormRequest
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
            'payment_type' => 'required|numeric',
            'serial_no' => 'required|numeric',
            'name' => 'required|string|max:50',
            'exp_date' => 'required|date',
            'bank_name' => 'required|string|max:50',

        ];
    }


    public function messages()
    {
        return [
            'payment_type.required' => 'this is field required',
            'serial_no.required' => 'this is field required',
            'name.required' => 'this is field required',
            'exp_date.required' => 'this is field required',
            'bank_name.required' => 'this is field required',
            


        ];
    }

}
