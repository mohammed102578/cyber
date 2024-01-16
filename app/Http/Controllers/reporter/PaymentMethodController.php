<?php

namespace App\Http\Controllers\reporter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reporter\Payment_methodRequest;
use App\Repository\reporter\PaymentMethodRepository;
use Illuminate\Http\Request;


class PaymentMethodController extends Controller
{
    protected $payment;
    public function __construct(PaymentMethodRepository $payment)
    {

        $this->payment = $payment;
    }


    public function store(Payment_methodRequest $request)
 {
    return $this->payment->store( $request);

 }
    //edit payment method
    public function edit(Request $request)
  {
    return $this->payment->edit( $request);

  }

    //update payment method
    public function update(Payment_methodRequest $request)
{
    return $this->payment->update( $request);

}

    //delete reporter
    public function destroy(Request $request)
  {
    return $this->payment->destroy( $request);

  }
}
