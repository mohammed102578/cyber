<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class update_Invoice_Request extends FormRequest
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
            'invoice_number'=>'required|numeric',
             'reporter_id'=>'required|numeric',
            'invoice_date'=>'required|date',
             'currency'=>'required|string',
             'total_amount'=>'required|numeric',
             'serial_no'=>'required|numeric',
             'serial_no_type'=>'required|string',
             'reference_number'=>'required|numeric',
             'explain_invoice'=>'required|string',
             'bank_name'=>'required|string',
             'report_id'=>'numeric',

          
         ];
     }
 
 
 
 
 
     public function messages()
     {
         return [
             'invoice_number.required' => 'this is field required',
             'invoice_date.required' => 'this is field required',
             'total_amount.required' => 'this is field required',
             'serial_no.required' => 'this is field required',
             'currency.required' => 'this is field required',
             'serial_no_type.required' => 'this is field required',
             'reference_number.required' => 'this is field required',
             'explain_invoice.required' => 'this is field required',
             'bank_name.required' => 'this is field required',
             'report_id.required' => 'this is field required',
         ];
     }
 
 }
 ?>