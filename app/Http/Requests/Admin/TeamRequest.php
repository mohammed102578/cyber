<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'image' =>'file|mimes:jpg,jpeg,png,gif|max:500000',
            'job' => 'required|min:3|string',
            'name' => 'required|min:3|string',
            'facebook' => 'required|min:3|string',
            'twitter' => 'required|min:3|string',
            'instagram' => 'required|min:3|string',
            'linkedIn' => 'required|min:3|string',

        ];
    }





    public function messages()
    {
        return [
            'name.required' => 'this is field required',
            'job.required' => 'this is field required',
            'facebook.required' => 'this is field required',
            'twitter.required' => 'this is field required',
            'instagram.required' => 'this is field required',
            'linkedIn.required' => 'this is field required',

        ];
    }

}
