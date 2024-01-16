<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
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
            'old_phone' => 'required|min:10',
            'new_phone' => 'required|min:10|unique:reporters,phone,'.auth()->user('reporter')->id,
            'password' => 'required|min:8',

        ];
    }





    public function messages()
    {
        return [
           
            'old_phone.required' => 'this is field required',
            'old_phone.min' => 'The field must contain at least ten characters.',


            'new_phone.required' => 'this is field required',
            'new_phone.unique' => 'phone already used.', 
            'new_phone.min' => 'The field must contain at least ten characters.',
           

        ];
    }

}
