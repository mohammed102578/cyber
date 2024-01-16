<?php

namespace App\Http\Services;

class SendOtpMobile  
{
   
    public function sendSMS($phone_number,$code)

    {


  
            $curl = curl_init();
            $from = "Daf3at";
            $to = "249".$phone_number;
            $message = "Verification Code is ".$code;
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://mazinhost.com/smsv1/sms/api",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "1",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "action=send-sms&api_key=eC5jMGQzcnowMDBAZ21haWwuY29tOk9nZHNGYnhVNmY=&to=$to&from=$from&sms=$message",
            ));
            $response = curl_exec($curl);
            curl_close($curl);
             $response;
    

    }
}
