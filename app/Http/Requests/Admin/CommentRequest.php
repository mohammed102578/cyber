<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'email' => 'required',
            'comment' => 'required|min:3',
            'blog_id' => 'required',
           
               ];
    }




    public function messages()
    {
        return [
            'name.required' => 'this is field required',   
            'name.min' => 'The field must contain at least three characters.',
            'email.required' => 'this is field required',
            'comment.required' => 'this is field required',
            'comment.min' => 'The field must contain at least three characters.',
        ];
    }

}
