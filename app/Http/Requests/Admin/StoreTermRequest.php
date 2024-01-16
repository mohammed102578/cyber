<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreTermRequest extends FormRequest
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

            'website_Terms_of_use' => 'required|min:3|string',
            'program_sponsor_terms_of_use' => 'required|min:3|string',
            'bounty_hunter_terms_of_use' => 'required|min:3|string',
            'mvdp_users_terms_of_use' => 'required|min:3|string',
            'mvdp_terms_of_use' => 'required|min:3|string',
        ];
    }


    public function messages()
    {
        return [
            'website_Terms_of_use.required' => 'this is field required',
            'program_sponsor_terms_of_use.required' => 'this is field required',
            'bounty_hunter_terms_of_use.required' => 'this is field required',
            'mvdp_users_terms_of_use.required' => 'this is field required',
            'mvdp_terms_of_use.required' => 'this is field required',


        ];
    }

}
