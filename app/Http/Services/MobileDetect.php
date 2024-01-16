<?php
 namespace App\Http\Services;

class MobileDetect
{
    public static function systemInfo()
    {
       $user_agent = $_SERVER['HTTP_USER_AGENT'];
       $os_platform    = "Unknown OS Platform";
       $os_array       = array('/windows phone 8/i'    =>  'Windows Phone 8',
                               '/windows phone os 7/i' =>  'Windows Phone 7',
                               '/windows nt 6.3/i'     =>  'Windows 8.1',
                               '/windows nt 6.2/i'     =>  'Windows 8',
                               '/windows nt 6.1/i'     =>  'Windows 7',
                               '/windows nt 6.0/i'     =>  'Windows Vista',
                               '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                               '/windows nt 5.1/i'     =>  'Windows XP',
                               '/windows xp/i'         =>  'Windows XP',
                               '/windows nt 5.0/i'     =>  'Windows 2000',
                               '/windows me/i'         =>  'Windows ME',
                               '/win98/i'              =>  'Windows 98',
                               '/win95/i'              =>  'Windows 95',
                               '/win16/i'              =>  'Windows 3.11',
                               '/macintosh|mac os x/i' =>  'Mac OS X',
                               '/mac_powerpc/i'        =>  'Mac OS 9',
                               '/linux/i'              =>  'Linux',
                               '/ubuntu/i'             =>  'Ubuntu',
                               '/iphone/i'             =>  'iPhone',
                               '/ipod/i'               =>  'iPod',
                               '/iPad/i'               =>  'iPad',
                               '/android/i'            =>  'Android',
                               '/blackberry/i'         =>  'BlackBerry',
                               '/webos/i'              =>  'Mobile');
       $found = false;
       $addr = $_SERVER['REMOTE_ADDR'];
       $device = '';
       foreach ($os_array as $regex => $value)
       {
           if($found)
            break;
           else if (preg_match($regex, $user_agent))
           {
               $os_platform    =   $value;
               $device = !preg_match('/(windows|mac|linux|ubuntu)/i',$os_platform)
                         ?'MOBILE':(preg_match('/phone/i', $os_platform)?'MOBILE':'Desktop');
           }
       }
       $device = !$device? 'Desktop':$device;
       return array('os'=>$os_platform,'device'=>$device);
    }

    public static function browser()
    {       $found = false;

       $user_agent = $_SERVER['HTTP_USER_AGENT'];

       $browser        =   "Unknown Browser";

       $browser_array  = array('/msie/i'       =>  'Internet Explorer',
                               '/firefox/i'    =>  'Firefox',
                               '/safari/i'     =>  'Safari',
                               '/chrome/i'     =>  'Chrome',
                               '/opera/i'      =>  'Opera',
                               '/netscape/i'   =>  'Netscape',
                               '/maxthon/i'    =>  'Maxthon',
                               '/konqueror/i'  =>  'Konqueror',
                               '/mobile/i'     =>  'Handheld Browser');

       foreach ($browser_array as $regex => $value)
       {
           if($found)
            break;
           else if (preg_match($regex, $user_agent,$result))
           {
               $browser    =   $value;
           }
       }
       return $browser;
    }





    public static function name_device()
    {       $found = false;

       $user_agent = $_SERVER['HTTP_USER_AGENT'];

       $device_name        =   "Unknown device_name";

       $device_name_array  = array(

       '/Blazer/i' => 'Blazer',
       '/Palm/i' => 'Palm',
       '/Handspring/i' => 'Handspring',
       '/Nokia/i' => 'Nokia',
       '/iphone/i'=>'iphone',
       '/Samsung/i' => 'Samsung' ,
       '/Motorola/i' => 'Motorola'  ,
       '/Smartphone/i'=>'Smartphone',
       '/Windows CE/i' => 'Windows CE' ,
       '/Blackberry/i' => 'Blackberry'   ,
       '/WAP/i' => 'WAP'  ,
       '/SonyEricsson/i' => 'SonyEricsson',
       '/PlayStation Portable/i' => 'PlayStation Portable',
       '/LG/i' => 'LG',
       '/MMP/i' => 'MMP',
       '/OPWV/i' => 'OPWV',
       '/Symbian/i' => 'Symbian',
       '/Ipad/i' => 'Ipad',
       '/Xiaomi/i' => 'Xiaomi',
       '/Huawei/i'=>'Huawei',

       '/Sony/i'=>'Sony',

       '/HTC/i'=>'HTC',

       '/Lenovo/i'=>'Lenovo',


       '/Google/i'=>'Google',

       '/Honor/i'=>'Honor',

       '/Oppo/i'=>'Oppo',

       '/Realme/i'=>'Realme',

       '/OnePlus/i'=>'OnePlus',

       '/vivo/i'=>'vivo',

       '/Meizu/i'=>'Meizu',

       '/BlackBerry/i'=>'BlackBerry',

       '/Asus/i'=>'Asus',

       '/Alcatel/i'=>'Alcatel',

       '/ZTE/i'=>'ZTE',

       '/Microsoft/i'=>'Microsoft',

       '/Vodafone/i'=>'Vodafone',

       '/Energizer/i'=>'Energizer',

       '/Cat/i'=>'Cat',

       '/Sharp/i'=>'Sharp',

       '/Micromax/i'=>'Micromax',

       '/Infinix/i'=>'Infinix',

       '/TCL/i'=>'TCL',

       '/Ulefone/i'=>'Ulefone',

       '/Tecno/i'=>'Tecno',

       '/Doogee/i'=>'Doogee',

       '/Blackview/i'=>'Blackview',

       '/BLU/i'=>'BLU',

       '/Panasonic/i'=>'Panasonic',

       '/Plum/i'=>'Plum',

       '/ubuntu/i'=>'computer',
       '/windows/i'=>'computer',



    );

       foreach ($device_name_array as $regex => $value)
       {
           if($found)
            break;
           else if (preg_match($regex, $user_agent,$result))
           {
               $device_name    =   $value;
           }
       }
       return $device_name;
    }




public static function icon(){
    $icon="";
    if(preg_match('/(windows|mac|linux|ubuntu|iPhone|Android|iPad|Unknown OS Platform)/i',MobileDetect::systemInfo()['os'])){
         $platform = MobileDetect::systemInfo()['os'];

        if($platform=="Windows"){
       $icon="bx bxl-windows text-info me-3";
        }elseif($platform=="Mac"){
         $icon="bx bxl-apple me-3";
       }elseif($platform=="Linux"){
         $icon="bx bx-desktop text-danger me-3";
       }elseif($platform=="Ubuntu"){
         $icon="bx bx-desktop text-danger me-3";
       }elseif($platform=="Iphone"){
         $icon="bx bx-mobile-alt text-danger me-3";
       }elseif($platform=="Android"){
         $icon="bx bxl-android text-success me-3";

     }elseif($platform=="Ipad"){
         $icon="bx bx-mobile-landscape text-info me-3";
       }else{
         $icon="bx bx-info-circle text-info me-3";
       }
    }
return $icon;
}






   }
