<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TargetRequest extends FormRequest
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

           
            'target' => 'required|min:3',
            'id' => 'integer',
           
          
        ];
    }




    public function messages()
    {
        return [
            'target.required' => 'this is field required',
            
           
            'target.min' => 'The field must contain at least three characters.',
           
           


        ];
    }

}
