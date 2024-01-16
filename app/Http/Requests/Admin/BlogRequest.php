<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
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
            'title' => 'required|min:3',
            'body' => 'required|min:3',
            'author' => 'required|min:3',
            'blog_category_id' => 'required|integer',
            'image' =>'file|mimes:jpg,jpeg,png,gif|max:500000',
            'tags' => 'required',
            'id' => 'integer',
               ];
    }




    public function messages()
    {
        return [
            'title.required' => 'this is field required',
            'title.min' => 'The field must contain at least three characters.',
            'body.required' => 'this is field required',
            'body.min' => 'The field must contain at least three characters.',
            'author.required' => 'this is field required',
            'author.min' => 'The field must contain at least three characters.',
            'blog_category_id.required' => 'this is field required',
            'tags.required' => 'this is field required',




        ];
    }

}
