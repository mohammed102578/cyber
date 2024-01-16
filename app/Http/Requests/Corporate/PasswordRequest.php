<?php

namespace App\Http\Requests\Corporate;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            
            'new_password' => 'required|min:8',
          
          
            'old_password' => 'required',

            'confirm_password' => 'required|same:new_password',
           
        ];
    }





    public function messages()
    {
        return [
           
           
            'new_password.min' => 'The password must contain at least eight characters.',
           
            'old_password.min' => 'The password must contain at least eight characters.',


        ];
    }

}
