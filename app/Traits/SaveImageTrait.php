<?php


namespace App\Traits;

trait SaveImageTrait
{

    public static function save_image($image_request,$image_place):string
    {

   $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://");
   $actual_link.$_SERVER['HTTP_HOST'];
   $image=$image_request;
   $newimage = time().".".$image->clientExtension();
   $image->move('uploade/'.$image_place, $newimage);
   return $actual_link.$_SERVER['HTTP_HOST'].'/uploade/'.$image_place.'/'.$newimage;

    }







}

?>
