<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
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
            'new_email' => 'required|unique:reporters,email,'.auth()->user('reporter')->id,
            'old_email' => 'required|email:rfc,dns',
            'password' => 'required|min:8',

        ];
    }


 


    public function messages()
    {
        return [
            'new_email.required' => 'this is field required',
            'new_email.email' => 'this is not a valid email address',
            'new_email.unique' => 'This email is already in use',
     
            'old_email.required' => 'this is field required',
            'old_email.email' => 'this is not a valid email address',
     

        ];
    }

}
