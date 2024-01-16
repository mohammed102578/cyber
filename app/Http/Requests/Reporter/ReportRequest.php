<?php

namespace App\Http\Requests\Reporter;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
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
            'summarize' => 'required',
            'target' => 'required',
            'vulnerability_id' => 'required',
            'belong_vulnerability_id' => 'required',
            'url_vulnerability' => 'required',
            'description' => 'required',
            'reproduce' => 'required|',
            'impact' => 'required',
            'recommendation' => 'required',
           
        ];
    }





    public function messages()
    {
        return [
            'summarize.required' => 'this is field required',
            'target.required' => 'this is field required',
            'vulnerability_id.required' => 'this is field required',
            'belong_vulnerability_id.required' => 'this is field required',
            'url_vulnerability.required' => 'this is field required',
            'description.required' => 'this is field required',
            'reproduce.required' => 'this is field required',
            'impact.required' => 'this is field required',
            'recommendation.required' => 'this is field required',
            


        ];
    }

}
