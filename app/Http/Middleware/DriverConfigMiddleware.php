<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class DriverConfigMiddleware
{
    public function __construct()
    {
        $this->setDrivers();
    }

    public function handle(Request $request, Closure $next)
    {
        Config::set('filesystems.default', get_static_option('storage_driver','CustomUploader'));
        return $next($request);
    }

    private function setDrivers(): void
    {
        $driver = get_static_option('storage_driver');

        if (in_array($driver, ['wasabi', 's3', 'cloudFlareR2']))
        {
            $db_name = match ($driver)
            {
                "wasabi" => "wasabi",
                "s3" => "aws",
                "cloudFlareR2" => "cloudflare_r2"
            };

            Config::set([
                "filesystems.disks.{$driver}.key" => get_static_option("{$db_name}_access_key_id") ?? Config::get("filesystems.disks.{$driver}.key"),
                "filesystems.disks.{$driver}.secret" => get_static_option("{$db_name}_secret_access_key") ?? Config::get("filesystems.disks.{$driver}.secret"),
                "filesystems.disks.{$driver}.region" => get_static_option("{$db_name}_default_region") ?? Config::get("filesystems.disks.{$driver}.region"),
                "filesystems.disks.{$driver}.bucket" => get_static_option("{$db_name}_bucket") ?? Config::get("filesystems.disks.{$driver}.bucket"),
                "filesystems.disks.{$driver}.endpoint" => get_static_option("{$db_name}_endpoint") ?? Config::get("filesystems.disks.{$driver}.endpoint"),
            ]);

            if (in_array($driver, ['s3', 'cloudFlareR2']))
            {
                Config::set([
                    "filesystems.disks.{$driver}.url" => get_static_option("{$db_name}_url") ?? Config::get("filesystems.disks.{$driver}.url"),
                    "filesystems.disks.{$driver}.use_path_style_endpoint" => true
                ]);
            }
        }
    }
}
