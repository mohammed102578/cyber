<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class TimeRequest extends FormRequest
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
            'from' => 'date',
            'to' => 'date',
          
           
        ];
    }





    public function messages()
    {
        return [
            'from.date' => 'this is field must be date',
            'to.date' => 'this is field must be date',
            
            


        ];
    }

}
