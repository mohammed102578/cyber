<?php

namespace App\Http\Requests\Corporate;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    $regex= '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        return [
            'email' => 'required|unique:corporates,email',
            'username' => 'required|min:3|unique:corporates,username',
            'password' => 'required|min:8',
            'company_name' => 'required|min:3',
            'confirm_password' => 'required|same:password',
            'website' => 'required|regex:'.$regex,
            'field' => 'required|min:3|string',
            'section' => 'required|min:3|string',
            'city' => 'required|min:3|string',
            'nationality' => 'required',
            'captcha' => 'required|captcha',
            'terms'=>'required'
        ];
    }





    public function messages()
    {
        return [
            'email.required' => 'this is field required',
            'username.required' => 'this is field required',
            'password.required' => 'this is field required',
            'company_name.required' => 'this is field required',
            'confirm_password.required' => 'this is field required',
            'website.required' => 'this is field required',
            'field.required' => 'this is field required',
            'section.required' => 'this is field required',
            'city.required' => 'this is field required',
            'nationality.required' => 'this is field required',
            'terms.required' => 'this is field required',
            'captcha.required' => 'this is field required',
            'captcha.captcha' => 'please Enter valid captcha',
            'username.unique' => 'Username already used.', 
            'email.unique' => 'E-mail already used.', 
            'password.min' => 'The password must contain at least eight characters.',
            'company_name.min' => 'The field must contain at least three characters.',
            'confirm_password.same' => 'Confirm password and password do not match.',
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
