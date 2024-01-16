<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
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
            'id' => 'required',
            'accountActivation' => 'required',
            'delete_password' => 'required',

        ];
    }


 


    public function messages()
    {
        return [
            'accountActivation.required' => 'this is field required',
            'delete_password.email' => 'this is not a valid email address',
           
        ];
    }

}
