<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        //force ssl
        if (get_static_option('site_force_ssl_redirection') === 'on'){
            URL::forceScheme('https');
        }

        //: this two method are only for loading pagebuilder blade file and menu builder blade file
        $this->loadViewsFrom(__DIR__.'/../../plugins/PageBuilder/views','pagebuilder');


        //added for cloud storage

        //Setup micros for mediauploader url or cloudlflareurl


        Storage::macro("renderUrl", function ($filepath, $size = null, $load_from = 0)
        {
            $prefix = !empty($size) ? '' : $size."/".$size."-";
            if ($size == ""){
                $prefix = "";
            }

            if ($prefix == "full"){
                $prefix = "";
            }

            $driver = Storage::getDefaultDriver();

            if ($load_from === 0 && Auth::guard('web')->check()){
                $driver = "CustomUploader";
            }

            $file_url = Storage::disk($driver)->url($prefix.$filepath);

            if($load_from == 0){
                return str_replace("/storage",url("/assets/uploads/"),$file_url);
            }

            $folder_prefix = "";

            if (cloudStorageExist() && Storage::getDefaultDriver() == "wasabi"){
                $finalUrl = renderWasabiCloudFile($filepath);

                return $finalUrl;
            }


            if (cloudStorageExist() && Storage::getDefaultDriver() == "s3"){
                $tempUrl = Storage::temporaryUrl($folder_prefix.$prefix.$filepath,Carbon::now()->addMinutes(20));
                return $tempUrl;
            }
            if(cloudStorageExist() && Storage::getDefaultDriver() == "CustomUploader"){
                return Storage::disk(Storage::getDefaultDriver())->url($folder_prefix.$prefix.$filepath);
            }
            $tempUrl = Storage::temporaryUrl($folder_prefix.$prefix.$filepath,Carbon::now()->addMinutes(20));

            //cloudflare temporary url
            $finalUrl = str_replace([
                "https://".get_static_option('cloudflare_r2_bucket').".".str_replace("https://","",get_static_option('cloudflare_r2_endpoint'))
            ],[
                "https://".get_static_option('cloudflare_r2_url')
            ],$tempUrl);

            return $finalUrl;
        });

    }
}
