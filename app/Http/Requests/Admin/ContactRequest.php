<?php

namespace App\Http\Requests\admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {

        return [
            'first_name' => 'required|min:2',
            'last_name' => 'required|min:2',
            'job' => 'required|min:3',
            'company' => 'required|min:3',
            'reason' => 'required|min:3',
            'email' => 'required|email:rfc,dns',
           
               ];
    }




    public function messages()
    {
        return [
            'first_name.required' => 'this is field required',   
            'first_name.min' => 'The field must contain at least tow characters.',
            'company.required' => 'this is field required',   
            'company.min' => 'The field must contain at least tow characters.',
            'last_name.required' => 'this is field required',   
            'last_name.min' => 'The field must contain at least tow characters.',
            'job.required' => 'this is field required',   
            'job.min' => 'The field must contain at least tow characters.',
            'email.required' => 'this is field required',
            'reason.required' => 'this is field required',
            'reason.min' => 'The field must contain at least tow characters.',
        ];
    }

}
