<?php

namespace App\Http\Requests\Corporate;

use Illuminate\Foundation\Http\FormRequest;

class UsernameRequest extends FormRequest
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
            'old_username' => 'required|min:10',
            'new_username' => 'required|min:10|unique:corporates,username,'.auth()->user('corporate')->id,
            'password' => 'required',
        ];
    }





    public function messages()
    {
        return [
           
            'old_username.required' => 'this is field required',
            'old_username.min' => 'The field must contain at least ten characters.',
            'password.required' => 'this is field required',
            'new_username.required' => 'this is field required',
            'new_username.unique' => 'username already used.', 
            'new_username.min' => 'The field must contain at least ten characters.',
           

        ];
    }

}
