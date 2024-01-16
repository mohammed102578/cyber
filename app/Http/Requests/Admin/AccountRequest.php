<?php

namespace App\Http\Requests\Admin;

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
            'name' => 'required|min:3',
            'image' =>'file|mimes:jpg,jpeg,png,gif|max:500000',

        ];
    }


    public function messages()
    {
        return [
            'name.required' => 'this is field required',
            'name.min' => 'The field must contain at least three characters.',
            
          


        ];
    }

}
