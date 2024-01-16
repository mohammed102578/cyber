<?php

namespace App\Providers;

use App\Models\Admin\PlatformSetting;
use Illuminate\Support\ServiceProvider;


class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('platform_setting', function () {
            $response=   PlatformSetting::first();
            if($response){
                return $response;
            }else{
                $response=new PlatformSetting();
                $response->name="not found";
                $response->phone="not found";
                $response->logo="not found";
                $response->address="not found";
                $response->facebook="not found";
                $response->email="not found";
                $response->description="not found";
                $response->twitter="not found";
                $response->instagram="not found";
                $response->linkedIn="not found";
                $response->company_logo="not found";
                $response->company_name="not found";
                return $response;

            }

        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
