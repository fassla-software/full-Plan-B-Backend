<?php

use App\Helper\ModuleMetaData;
use App\Helper;


function module_dir($moduleName)
{
    return 'core/Modules/' . $moduleName . '/';
}


if (!function_exists('renderWasabiCloudFile')) {
    function renderWasabiCloudFile($fileLocation): string
    {
        $s3 = new \Aws\S3\S3Client([
            'endpoint' => get_static_option('wasabi_endpoint') ?? config('filesystems.disks.wasabi.endpoint'),
            'region' => get_static_option('wasabi_default_region') ?? config('filesystems.disks.wasabi.region'),
            'version' => 'latest',
            'credentials' => array(
                'key' => get_static_option('wasabi_access_key_id') ?? config('filesystems.disks.wasabi.key'),
                'secret' => get_static_option('wasabi_secret_access_key') ?? config('filesystems.disks.wasabi.secret'),
            )
        ]);

        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => get_static_option('wasabi_bucket') ?? config('filesystems.disks.wasabi.bucket'),
            'Key' => $fileLocation,
            'ACL' => 'public-read',
        ]);

        $request = $s3->createPresignedRequest($cmd, '+20 minutes');
        $img_url = (string)$request->getUri();

        return $img_url;
    }
}

function cloudStorageExist(): bool
{
    return (moduleExists('CloudStorage') && isPluginActive('CloudStorage'));
}
