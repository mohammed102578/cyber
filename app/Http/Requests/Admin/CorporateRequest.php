<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CorporateRequest extends FormRequest
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

            'email' => 'required|email|unique:corporates,email,'.$this->id,
            'username' => 'required|min:3|unique:corporates,username,'.$this->id,
            'password' => 'nullable|min:8',
            'company_name' => 'required|min:3',
            'website' => 'required|string',
            'field' => 'required|min:3|string',
            'section' => 'required|min:3|string',
            'city' => 'required|min:3|string',
            'nationality' => 'required',
            'id' => 'required',

        ];
    }




    public function messages()
    {
        return [
            'email.required' => 'this is field required',
            'username.required' => 'this is field required',
            'company_name.required' => 'this is field required',
            'website.required' => 'this is field required',
            'field.required' => 'this is field required',
            'section.required' => 'this is field required',
            'city.required' => 'this is field required',
            'nationality.required' => 'this is field required',
            'username.unique' => 'Username already used.',
            'email.unique' => 'E-mail already used.',
            'company_name.min' => 'The field must contain at least three characters.',
            'city.min' => 'The field must contain at least three characters.',
            'field.min' => 'The field must contain at least three characters.',
            'username.min' => 'The field must contain at least three characters.',
            'section.min' => 'The field must contain at least three characters.',
            'city.string' => 'The field must contain just characters.',
            'section.string' => 'The field must contain just characters.',
            'field.string' => 'The field must contain just characters.',
            'website.regex' => 'Please enter a valid web address.',


        ];
    }

}
