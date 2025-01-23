<?php

namespace App\Jobs;

//use App\Http\Middleware\Tenant\TenantConfigMiddleware;
use App\Models\IdentityVerification;
use App\Models\JobPost;
use App\Models\JobProposal;
use App\Models\MediaUpload;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\SupportTicket\Entities\ChatMessage;

class SyncLocalFileWithCloud implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $file, public $type){ }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setDrivers();
        $item = $this->file;
        $folder_type = $this->type;


        if($folder_type == 'media-uploader/') {
            //run query from the database get all media file then run loop and send file to the jobs done it through queue, update database that this file is already synced
            $local_file_path = base_path("../assets/uploads/" . $folder_type . $item?->path);
            $cl_file_path = $folder_type . $item?->path;

            //check the file already exists in the cloud if not exits then create the copy that file to cloud
            if (!empty($item->path) && file_exists($local_file_path)) {
                try {
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed = new File($local_file_path);
                        Storage::putFileAs("/" . $folder_type, $fileNeed, $item->path, 'public');
                        MediaUpload::find($item->id)->update(["is_synced" => 1, 'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    MediaUpload::find($item->id)->update(["is_synced" => 1, 'load_from' => 1]);
                } catch (\Exception $e) {
                }
            }
        }

        if($folder_type == 'project/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->image);
            $cl_file_path = $folder_type.$item?->image;

            if(!empty($item->image) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->image, 'public');
                        Project::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    Project::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }

        if($folder_type == 'jobs/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->attachment);
            $cl_file_path = $folder_type.$item?->attachment;

            if(!empty($item->attachment) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->attachment, 'public');
                        JobPost::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    JobPost::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }

        if($folder_type == 'jobs/proposal/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->attachment);
            $cl_file_path = $folder_type.$item?->attachment;

            if(!empty($item->attachment) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->attachment, 'public');
                        JobProposal::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    JobProposal::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }

        if($folder_type == 'portfolio/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->image);
            $cl_file_path = $folder_type.$item?->image;

            if(!empty($item->image) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->image, 'public');
                        Portfolio::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    Portfolio::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }

        if($folder_type == 'profile/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->image);
            $cl_file_path = $folder_type.$item?->image;

            if(!empty($item->image) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->image, 'public');
                        User::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    User::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }

        if($folder_type == 'ticket/chat-messages/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->attachment);
            $cl_file_path = $folder_type.$item?->attachment;

            if(!empty($item->attachment) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->attachment, 'public');
                        ChatMessage::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    ChatMessage::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }

        if($folder_type == 'verification/') {
            $local_file_path = base_path("../assets/uploads/".$folder_type.$item?->front_image);
            $cl_file_path = $folder_type.$item?->front_image;

            $back_local_file_path = base_path("../assets/uploads/".$folder_type.$item?->back_image);
            $back_cl_file_path = $folder_type.$item?->front_image;

            //for front image
            if(!empty($item->front_image) && file_exists($local_file_path)){
                try{
                    if (!Storage::exists($cl_file_path)) {
                        $fileNeed =  new File($local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->front_image, 'public');
                        IdentityVerification::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    IdentityVerification::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }

            //for back image
            if(!empty($item->back_image) && file_exists($back_local_file_path)){
                try{
                    if (!Storage::exists($back_cl_file_path)) {
                        $fileNeed =  new File($back_local_file_path);
                        Storage::putFileAs("/".$folder_type,$fileNeed,$item->back_image, 'public');
                        IdentityVerification::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                    }
                    /* change the database status to is_synced because the file is already exits on the cloud */
                    IdentityVerification::find($item->id)->update(["is_synced" => 1,'load_from' => 1]);
                }catch(\Exception $e){}
            }
        }
    }

    private function setDrivers()
    {
        $driver = get_static_option('storage_driver' , 'CustomUploader');

        if (in_array($driver, ['wasabi', 's3', 'cloudFlareR2']))
        {
            $db_name = match ($driver)
            {
                "wasabi" => "wasabi",
                "s3" => "aws",
                "cloudFlareR2" => "cloudflare_r2"
            };

            Config::set([
                "filesystems.default" => $driver,
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
