<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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

            'title' => 'required|min:3|string',
            'body' => 'required|min:3|string',
            'reporter' => 'required',
            'corporate' => 'required',
           
          
        ];
    }




    public function messages()
    {
        return [
            'title.required' => 'this is field required',
            'body.required' => 'this is field required',
            'reporter.required' => 'this is field required',
            'corporate.required' => 'this is field required',
            'company_name.min' => 'The field must contain at least three characters.',
            'title.min' => 'The field must contain at least three characters.',
            'body.min' => 'The field must contain at least three characters.',
            'title.string' => 'The field must contain just characters.',
            'body.string' => 'The field must contain just characters.',


        ];
    }

}
