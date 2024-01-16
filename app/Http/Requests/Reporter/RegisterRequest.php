<?php

namespace App\Http\Requests\Reporter;

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

        return [
            'email' => 'required|email:rfc,dns|unique:reporters,email',
            'phone' => 'required|min:9|unique:reporters,phone',
            'password' => 'required|min:8',
            'first_name' => 'required|min:3',
            'confirm_password' => 'required|same:password',
            'last_name' => 'required|',
            'birthday' => 'required',
            'company' => 'min:3|string',
            'city' => 'required|min:3|string',
            'nationality' => 'required',
            'captcha' => 'required|captcha',
            'terms'=>'required',
            'hobby'=>'required',
            'job'=> 'required',
        ];
    }





    public function messages()
    {
        return [
            'email.required' => 'this is field required',
            'hobby.required' => 'this is field required',
            'phone.required' => 'this is field required',
            'password.required' => 'this is field required',
            'first_name.required' => 'this is field required',
            'confirm_password.required' => 'this is field required',
            'job.required' => 'this is field required',
            'last_name.required' => 'this is field required',
            'birthday.required' => 'this is field required',
            'city.required' => 'this is field required',
            'nationality.required' => 'this is field required',
            'terms.required' => 'this is field required',
            'captcha.required' => 'this is field required',
            'captcha.captcha' => 'please Enter valid captcha',
            'phone.unique' => 'phone already used.', 
            'email.unique' => 'E-mail already used.', 
            'password.min' => 'The password must contain at least eight characters.',
            'first_name.min' => 'The field must contain at least three characters.',
            'confirm_password.same' => 'Confirm password and password do not match.',
            'city.min' => 'The field must contain at least three characters.',
            'last_name.min' => 'The field must contain at least three characters.',
            'phone.min' => 'The field must contain nine  number.',
            'company.min' => 'The field must contain at least three characters.',
            'city.string' => 'The field must contain just characters.',
            'company.string' => 'The field must contain just characters.',


        ];
    }

}
