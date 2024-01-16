<?php

namespace App\Interfaces\reporter;

interface PaymentMethodInterface
{

  
    public function store($request);
    //edit payment method
    public function edit($request);
    //update payment method      
    public function update($request);
    //delete reporter
    public function destroy($request);
   
}
