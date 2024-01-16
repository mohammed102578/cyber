<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePrivacyRequest extends FormRequest
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

            'privacy_policy' => 'required|min:3|string',
            'disclosure_policy' => 'required|min:3|string',
            'code_of_conduct' => 'required|min:3|string',


        ];
    }


    public function messages()
    {
        return [
            'privacy_policy.required' => 'this is field required',
            'disclosure_policy.required' => 'this is field required',
            'code_of_conduct.required' => 'this is field required',


        ];
    }

}
