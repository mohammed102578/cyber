<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePlatformSettingRequest extends FormRequest
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
            'logo' =>'file|mimes:jpg,jpeg,png,gif|max:500000',
            'email' => 'required|email',
            'phone' => 'required|min:3|string',
            'address' => 'required|min:3|string',
            'name' => 'required|min:3|string',
            'description' => 'required|min:3|string',
            'facebook' => 'required|min:3|string',
            'twitter' => 'required|min:3|string',
            'instagram' => 'required|min:3|string',
            'linkedIn' => 'required|min:3|string',
            'company_log'=> 'file|mimes:jpg,jpeg,png,gif|max:500000',
            'company_name'=> 'required|min:3|string',
        ];
    }





    public function messages()
    {
        return [
            'email.required' => 'this is field required',
            'phone.required' => 'this is field required',
            'name.required' => 'this is field required',
            'description.required' => 'this is field required',
            'address.required' => 'this is field required',
            'facebook.required' => 'this is field required',
            'twitter.required' => 'this is field required',
            'instagram.required' => 'this is field required',
            'linkedIn.required' => 'this is field required',
            'company_name.required' => 'this is field required',
           
        ];
    }

}
