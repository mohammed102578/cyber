<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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

           
            'question' => 'required|min:3',
            'answer' => 'required|min:3',
            'id' => 'integer',
            'faq_class_id'=>'required|integer'

          
        ];
    }




    public function messages()
    {
        return [
            'question.required' => 'this is field required',   
            'question.min' => 'The field must contain at least three characters.',
            'answer.required' => 'this is field required',
            'answer.min' => 'The field must contain at least three characters.',
           
           


        ];
    }

}
