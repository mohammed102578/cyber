<?php

namespace App\Http\Requests\Corporate;

use Illuminate\Foundation\Http\FormRequest;

class Update_program_Request extends FormRequest
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
            'image' =>'file|mimes:jpg,jpeg,png,gif|max:500000',
             'reporter_quantity' =>'required',
             'program_type' => 'required',
             'management' => 'required',
             'currency' => 'required',
             'description_ar' => 'required|min:10',
             'description_en' => 'required|min:10',
             'range_to' => 'required|array',
             'range_from' => 'required|array',
          
         ];
     }
 
 
 
 
 
     public function messages()
     {
         return [
             'reporter_quantity.required' => 'this is field required',
             'program_type.required' => 'this is field required',
             'management.required' => 'this is field required',
             'currency.required' => 'this is field required',
             'description_ar.required' => 'this is field required',
             'description_en.required' => 'this is field required',
             'range_from.required' => 'all fields in range_form are required',
             'range_to.required' => 'all fields in range_to are required',

         ];
     }
 
 }
 ?>