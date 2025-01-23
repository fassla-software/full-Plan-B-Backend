<?php

namespace App\Http\Controllers\Api\Client;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\JobHistory;
use App\Models\JobPost;
use App\Models\JobProposal;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Chat\Entities\LiveChat;
use Modules\Service\Entities\SubCategory;

class JobController extends Controller
{
    //skill lists
    public function skill(Request $request)
    {
        if(!empty($request->skill)){
            $allSkills = Skill::select(['id','skill','status'])->where('status',1)
                ->where('skill', 'LIKE', "%". strip_tags($request->skill) ."%")
                ->paginate(10)->withQueryString();
        }else{
            $allSkills = Skill::select(['id','skill','status'])->where('status',1)->paginate(10)->withQueryString();
        }

        if($allSkills){
            return response()->json([
                'allSkills' => $allSkills
            ]);
        }
        return response()->json(['msg' => __('No skill found')]);
    }
    //job create
    public function job_create(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request->validate([
                'title'=>'required|min:5|max:100',
                'slug'=>'required|max:191|unique:job_posts,slug',
                'category'=>'required',
                'duration'=>'required|max:191',
                'level'=>'required|max:191',
                'description'=>'required|min:10',
                'type'=>'required|max:191',
                'skill'=>'required',
            ]);

            if($request->type == 'fixed'){
                $request->validate([
                    'budget'=>'required|numeric|gt:0',
                ]);
            }else{
                if(moduleExists('HourlyJob')){
                    $request->validate([
                        'hourly_rate'=>'required|numeric|gt:0',
                        'estimated_hours'=>'required|numeric|gt:0',
                    ]);
                }else{
                    return response()->json(['msg'=>__('Hourly job plugin is required')])->setStatusCode('422');
                }
            }

            $attachmentName = '';
            $upload_folder = 'jobs';
            $storage_driver = Storage::getDefaultDriver();

            if ($attachment = $request->file('attachment')) {

                $allowedSize = get_static_option('max_upload_size') ?? '5120';
                $allowedExtensions = json_decode(get_static_option('file_extensions'), true);

                if($allowedExtensions){
                    $allowed_extensions = implode(',', $allowedExtensions);
                    $request->validate([
                        'attachment' => 'required|mimes:' . $allowed_extensions . '|max:' . $allowedSize,
                    ]);
                }else{
                    $request->validate([
                        'attachment'=>'required|mimes:png,jpg,jpeg,bmp,gif,tiff,svg,csv,txt,xlx,xls,pdf,docx|max:5120',
                    ]);
                }

                $attachmentName = time().'-'.uniqid().'.'.$attachment->getClientOriginalExtension();
                $extensions = array('png','jpg','jpeg','bmp','gif','tiff','svg');

                if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                    if(in_array($attachment->getClientOriginalExtension(), $extensions)){
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachmentName,'public');
                    }else{
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachmentName,'public');
                    }
                }else{
                     $attachment->move('assets/uploads/jobs', $attachmentName);
                }
            }

            $user_id = auth('sanctum')->user()->id;
            $slug = !empty($request->slug) ? $request->slug : $request->title;

            $job = JobPost::create([
                'user_id'=>$user_id,
                'title'=>$request->title,
                'slug' => Str::slug(purify_html($slug),'-',null),
                'category'=>$request->category,
                'duration'=>$request->duration,
                'level'=>$request->level,
                'description'=>$request->description,
                'type'=>$request->type,
                'hourly_rate'=>$request->hourly_rate,
                'estimated_hours'=>$request->estimated_hours,
                'budget'=>$request->budget ?? 0,
                'attachment'=>$attachmentName,
                'status'=> get_static_option('job_auto_approval')  == 'no' ? 0 : 1,
                'job_approve_request'=>  1,
                'load_from' => in_array($storage_driver,['CustomUploader']) ? 0 : 1,
            ]);

            $job->job_sub_categories()->attach(json_decode($request->subcategory,true));
            $job->job_skills()->attach(json_decode($request->skill,true));

            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Job create','Client');
            }

            try {
                $message = get_static_option('job_create_email_message') ?? __('New job has been published.');
                $message = str_replace(["@job_id"],[$job->id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('job_create_email_subject') ?? __('New Job'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}

            //create project notification to admin
            $admin_notification = AdminNotification::create([
                'identity'=>$job->id,
                'user_id'=>$user_id,
                'type'=>__('Job'),
                'message'=>__('New job has been published.'),
            ]);

            if($admin_notification){
                return response()->json(['msg' => __('Job successfully Created')]);
            }
        }
    }

    //job edit
    public function job_edit(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $job_details = JobPost::where('id',$request->job_id)->where('user_id',$user_id)->first();
        $slug = !empty($request->slug) ? $request->slug : $request->title;
        $delete_old_attachment =  'assets/uploads/jobs/'.$job_details->attachment;

        if($request->isMethod('post'))
        {
            $request->validate([
                'title'=>'required|min:5|max:100',
                'slug'=>'required|max:191|unique:job_posts,slug,'.$request->job_id,
                'category'=>'required',
                'duration'=>'required|max:191',
                'level'=>'required|max:191',
                'description'=>'required|min:10',
                'type'=>'required|max:191',
                'skill'=>'required',
            ]);

            if($request->type == 'fixed'){
                $request->validate([
                    'budget'=>'required|numeric|gt:0',
                ]);
            }else{
                if(moduleExists('HourlyJob')){
                    $request->validate([
                        'hourly_rate'=>'required|numeric|gt:0',
                        'estimated_hours'=>'required|numeric|gt:0',
                    ]);
                }else{
                    return response()->json(['msg'=>__('Hourly job plugin is required')])->setStatusCode('422');
                }

            }

            $attachmentName = '';
            $upload_folder = 'jobs';
            $extensions = array('png','jpg','jpeg','bmp','gif','tiff','svg');

            $allowedSize = get_static_option('max_upload_size') ?? '5120';
            $allowedExtensions = json_decode(get_static_option('file_extensions'), true);

            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {

                if ($attachment = $request->file('attachment')) {

                    if($allowedExtensions){
                        $allowed_extensions = implode(',', $allowedExtensions);
                        $request->validate([
                            'attachment' => 'required|mimes:' . $allowed_extensions . '|max:' . $allowedSize,
                        ]);
                    }else{
                        $request->validate([
                            'attachment'=>'required|mimes:png,jpg,jpeg,bmp,gif,tiff,svg,csv,txt,xlx,xls,pdf,docx|max:5120',
                        ]);
                    }

                    $currentImagePath = $job_details->attachment;
                    if ($currentImagePath) {
                        delete_frontend_cloud_image_if_module_exists('jobs/'.$currentImagePath);
                    }

                    $attachmentName = time().'-'.uniqid().'.'.$attachment->getClientOriginalExtension();
                    if(in_array($attachment->getClientOriginalExtension(), $extensions)){
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachmentName,'public');
                    }else{
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachmentName,'public');
                    }
                }else{
                    $attachmentName = $job_details->attachment;
                }
            }else{
                if ($attachment = $request->file('attachment')) {
                    if($allowedExtensions){
                        $allowed_extensions = implode(',', $allowedExtensions);
                        $request->validate([
                            'attachment' => 'required|mimes:' . $allowed_extensions . '|max:' . $allowedSize,
                        ]);
                    }else{
                        $request->validate([
                            'attachment'=>'required|mimes:png,jpg,jpeg,bmp,gif,tiff,svg,csv,txt,xlx,xls,pdf,docx|max:5120',
                        ]);
                    }

                    if(file_exists($delete_old_attachment)){
                        File::delete($delete_old_attachment);
                    }
                    $attachmentName = time().'-'.uniqid().'.'.$attachment->getClientOriginalExtension();
                    $attachment->move('assets/uploads/jobs', $attachmentName);
                }else{
                    $attachmentName = $job_details->attachment;
                }
            }

            JobPost::where('id',$request->job_id)->update([
                'user_id'=>$user_id,
                'title'=>$request->title,
                'slug' => Str::slug(purify_html($slug),'-',null),
                'category'=>$request->category,
                'duration'=>$request->duration,
                'level'=>$request->level,
                'description'=>$request->description,
                'type'=>$request->type,
                'hourly_rate'=>$request->hourly_rate,
                'estimated_hours'=>$request->estimated_hours,
                'budget'=>$request->budget ?? 0,
                'attachment'=>$attachmentName,
            ]);

            $job = JobPost::find($request->job_id);
            $job->job_sub_categories()->sync(json_decode($request->subcategory,true));
            $job->job_skills()->sync(json_decode($request->skill,true));
            $job_id_from_job_history_table = JobHistory::where('job_id', $request->job_id)->first();

            if(empty($job_id_from_job_history_table)){
                JobHistory::Create([
                    'job_id'=>$job->id,
                    'user_id'=>$job->user_id,
                    'reject_count'=>0,
                    'edit_count'=>1,
                ]);
            }else{
                JobHistory::where('job_id',$request->job_id)->update([
                    'reject_count'=>$job_id_from_job_history_table->edit_count + 1
                ]);
            }

            //security manage
            if(moduleExists('SecurityManage')){
                LogActivity::addToLog('Job edit','Client');
            }

            try {
                $message = get_static_option('job_edit_email_message') ?? __('A job has been edited.');
                $message = str_replace(["@job_id"],[$job->id], $message);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail([
                    'subject' => get_static_option('job_edit_email_subject') ?? __('Job Edit Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}

            //edit job notification to admin
            $admin_notification = AdminNotification::create([
                'identity'=>$job->id,
                'user_id'=>$user_id,
                'type'=>__('Edit Job'),
                'message'=>__('A Job has been edited'),
            ]);

            if($admin_notification){
                return response()->json(['msg' => __('Job successfully Updated')]);
            }
        }
    }

    //all jobs
    public function all_job()
    {
        $user_id = auth('sanctum')->user()->id;
        if(moduleExists('HourlyJob')){
            $all_jobs = JobPost::select(['id','title','description','type','level','status','on_off','current_status','created_at'])
                ->withCount('job_proposals')
                ->where('user_id',$user_id)
                ->latest()
                ->paginate(10)->withQueryString();
        }else{
            $all_jobs = JobPost::select(['id','title','description','type','level','status','on_off','current_status','created_at'])
                ->withCount('job_proposals')
                ->where('user_id',$user_id)
                ->where('type','fixed')
                ->latest()
                ->paginate(10)->withQueryString();
        }

        $active_jobs = JobPost::where('current_status',1)->where('user_id',$user_id)->count();
        $complete_jobs = JobPost::where('current_status',2)->where('user_id',$user_id)->count();
        $closed_jobs = JobPost::where('on_off',0)->where('user_id',$user_id)->count();

        $top_projects = Project::select('id','title','slug','user_id','basic_regular_charge','basic_discount_charge','basic_delivery','description','image')
            ->where('project_on_off','1')
            ->whereHas('project_creator')
            ->where('status','1')
            ->latest()
            ->take(3)
            ->get();

        if($all_jobs){
            return response()->json([
                'all_jobs' => $all_jobs,
                'active_jobs' => $active_jobs,
                'complete_jobs' => $complete_jobs,
                'closed_jobs' => $closed_jobs,
                'top_projects' => $top_projects,
                'project_image_path' => asset('assets/uploads/project/'),
            ]);
        }
        return response()->json(['msg' => __('no order found.')]);
    }

    //job details
    public function job_details($id)
    {
        $user_id = auth('sanctum')->user()->id;
        $job_details = JobPost::with([
            'job_creator:id,first_name,last_name,image,experience_level',
            'job_skills:id,skill,category_id',
            'job_proposals.freelancer_introduction_title_for_api:user_id,title',
            'job_proposals.freelancer:id,first_name,last_name,image,load_from',
            'job_proposals.freelancer_country_for_api',
            'job_proposals.freelancer_state_for_api',
            'job_proposals.live_chat_for_api',
            'job_proposals' => function ($query){
                $query->withCount('complete_order_count_api')->withCount('freelancer_ratings')->withAvg('freelancer_ratings','rating');
            },
            'job_category:id,category',
            'job_sub_categories:id,sub_category'
        ])
            ->where('id',$id)
            ->where('user_id',$user_id)
            ->first();

        $hired_freelancer_count = JobProposal::where('job_id',$id)->where('is_hired',1)->count();
        $short_listed_freelancer_count = JobProposal::where('job_id',$id)->where('is_hired',0)->where('is_rejected',0)->where('is_short_listed',1)->count();
        $interviewed_freelancer_count = JobProposal::where('job_id',$id)->where('is_rejected',0)->where('is_interview_take',1)->count();

        JobPost::where('id',$id)->update(['last_seen'=>date('Y-m-d H:i:s')]);


        if ($job_details && $job_details->job_proposals) {
            foreach ($job_details->job_proposals as $proposal) {
                if ($proposal->freelancer) {
                    $proposal->freelancer->cloud_image = $proposal->freelancer->image
                        ? render_frontend_cloud_image_if_module_exists('profile/'.$proposal->freelancer?->image, load_from: $proposal->freelancer->load_from)
                        : null;
                }
            }
        }


        if($job_details){
            if (! file_exists(base_path('../assets/uploads/jobs/' . $job_details->attachment))) {
                $job_details->attachment = '';
            }
            return response()->json([
                'job_details' => $job_details,
                'hired_freelancer_count' => $hired_freelancer_count,
                'short_listed_freelancer_count' => $short_listed_freelancer_count,
                'interviewed_freelancer_count' => $interviewed_freelancer_count,
                'freelancer_image_path' => asset('assets/uploads/profile/'),
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('no jobs found.')]);
    }

    //filter job proposal
    public function job_proposal_filter(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $job_proposals = JobProposal::where('job_id',$request->job_id)->where('client_id',$user_id)->latest();
        if(empty($job_proposals)){
            return response()->json(['msg'=>__('This job is not related to you')])->setStatusCode('422');
        }

        if($request->filter_val == 'all'){
            $job_proposals = $job_proposals->get();
        }
        if($request->filter_val == 'hired'){
            $job_proposals = $job_proposals->where('is_hired',1)->get();
        }
        if($request->filter_val == 'shortlisted'){
            $job_proposals = $job_proposals->where('is_hired',0)->where('is_rejected',0)->where('is_short_listed',1)->get();
        }
        if($request->filter_val == 'interviewing'){
            $job_proposals = $job_proposals->where('is_hired',0)->where('is_short_listed',0)->where('is_rejected',0)->where('is_interview_take',1)->get();
        }

        if($job_proposals){
            return response()->json([
                'filter_job_proposals' => $job_proposals,
            ]);
        }
        return response()->json(['msg' => __('no proposals found.')]);
    }

    //add to shortlist
    public function add_remove_shortlist(Request $request)
    {
        $proposal = JobProposal::where('id',$request->proposal_id)->first();
        $is_short_listed = $proposal->is_short_listed == 0 ? 1 : 0;
        JobProposal::where('id',$request->proposal_id)->update(['is_short_listed'=>$is_short_listed]);
        return response()->json(['status'=>$is_short_listed]);
    }

    //reject proposal
    public function reject_proposal(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $job_proposal = JobProposal::where('id',$request->proposal_id)->first();
        if($job_proposal){
            if($job_proposal->client_id != $user_id){
                return response()->json(['msg'=>__('This proposal is not related to you')])->setStatusCode('422');
            }
            JobProposal::where('id',$request->proposal_id)->update(['is_rejected' => 1]);
            return response()->json(['status' => 1]);
        }else{
            return response()->json(['msg'=>__('This proposal is not related to you')])->setStatusCode('422');
        }
    }

    //job open close
    public function open_close(Request $request)
    {
        $user_id = auth('sanctum')->user()->id;
        $job = JobPost::where('id',$request->job_id)->first();
        if($job){
            if($job->user_id != $user_id){
                return response()->json(['msg'=>__('This job is not related to you')])->setStatusCode('422');
            }
            $open_or_close = $job->on_off == 0 ? 1 : 0;
            JobPost::where('id',$request->job_id)->update(['on_off'=>$open_or_close]);
            return response()->json(['status'=>$open_or_close]);
        }else{
            return response()->json(['msg'=>__('This job is not related to you')])->setStatusCode('422');
        }

    }

}
