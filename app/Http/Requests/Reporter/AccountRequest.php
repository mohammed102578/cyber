<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'first_name' => 'required|min:3',
            'last_name' => 'required|',
            'birthday' => 'required',
            'company' => 'min:3|string',
            'city' => 'required|min:3|string',
            'nationality' => 'required',
            'job'=> 'required',
            'image' =>'file|mimes:jpg,jpeg,png,gif|max:500000',

        ];
    }


    public function messages()
    {
        return [
            'first_name.required' => 'this is field required',
            'job.required' => 'this is field required',
            'last_name.required' => 'this is field required',
            'birthday.required' => 'this is field required',
            'city.required' => 'this is field required',
            'nationality.required' => 'this is field required',
            'first_name.min' => 'The field must contain at least three characters.',
            'city.min' => 'The field must contain at least three characters.',
            'last_name.min' => 'The field must contain at least three characters.',
            'company.min' => 'The field must contain at least three characters.',
            'city.string' => 'The field must contain just characters.',
          


        ];
    }

}
