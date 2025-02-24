<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\Bookmark;
use App\Models\CraneRentalJob;
use App\Models\HeavyEquipmentJob;
use App\Models\Job;
use App\Models\JobHistory;
use App\Models\JobPost;
use App\Models\Order;
use App\Models\User;
use App\Models\VehicleRentalJob;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function all_job(Request $request)
    {

        // Define all equipment models
        $equipmentModels = [
            HeavyEquipmentJob::class,
            VehicleRentalJob::class,
            CraneRentalJob::class, // Add other models if needed
        ];

        $perPage = $request->get('per_page', 10); // Default to 10 items per page
        $page = $request->get('page', 1); // Get the current page number

        $allEquipment = collect();

        foreach ($equipmentModels as $model) {
            // Eager load the subCategory relation with the image field only
            $equipmentList = $model::with('subCategory:id,image')
                ->get()
                ->map(function ($equipment) {
                    // Filter out null values from the equipment record
                    $filteredEquipment = collect($equipment)->filter(function ($value) {
                        return !is_null($value);
                    });

                    // Attach the sub-category image if it exists
                    if ($equipment->subCategory && $equipment->subCategory->image) {
                        $filteredEquipment['sub_category_image'] = $this->getFullImageUrl($equipment->subCategory->image);
                    } else {
                        $filteredEquipment['sub_category_image'] = $this->getDefaultImageUrl();
                    }

                    // Remove the `subCategory` relation from the response
                    $filteredEquipment->forget('subCategory');

                    // Only include non-empty equipment data
                    return $filteredEquipment->isNotEmpty() ? $filteredEquipment : null;
                })
                ->filter();

            // Merge all equipment into a single collection
            $allEquipment = $allEquipment->merge($equipmentList);
        }

        // Paginate the combined equipment list
        $paginated = new LengthAwarePaginator(
            $allEquipment->forPage($page, $perPage),
            $allEquipment->count(),
            $perPage,
            $page,
            ['path' => url()->current(), 'query' => $request->query()]
        );
        return view('backend.pages.job.all-job',compact('paginated'));
//        // all jobs
//        $all_jobs = JobPost::whereHas('job_creator')->where('type','fixed')->latest()->paginate(10);
//        return view('backend.pages.job.all-job',compact('all_jobs'));
    }

    /**
     * Returns a default image URL if the actual image is missing.
     */
    private function getDefaultImageUrl()
    {
        return asset('assets/uploads/no-image.png');
    }

    private function getFullImageUrl($imageId)
    {
        if (!$imageId) {
            return null;
        }
        $imageDetails = get_attachment_image_by_id($imageId);
        return $imageDetails['img_url'] ?? null;
    }

    // search job
    public function search_job(Request $request)
    {
        $all_jobs= JobPost::whereHas('job_creator')->where('type','fixed')->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_jobs->total() >= 1 ? view('backend.pages.job.search-result', compact('all_jobs'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            if(!empty($request->string_search)){
                $all_jobs= JobPost::whereHas('job_creator')->where('type','fixed')->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
            }else{
                $all_jobs = JobPost::whereHas('job_creator')->latest()->paginate(10);
            }
            return view('backend.pages.job.search-result', compact('all_jobs'))->render();
        }
    }

    //  job details
    public function job_details($id=null)
    {
        JobPost::findOrFail($id);
        $job = JobPost::with(['job_category','job_history'])->whereHas('job_creator')->where('id',$id)->first();

        if($job){
            $user = User::with(['user_introduction','user_country','user_state','user_city'])->where('id',$job->user_id)->first();
            $complete_jobs_count = Order::where('is_project_job','job')->where('status',3)->where('user_id',$job->user_id)->get();
            AdminNotification::where('identity',$id)->update(['is_read'=>1]);
            return isset($job) ? view('backend.pages.job.job-details',compact(['job','user','complete_jobs_count'])) : back();
        }else{
            return back();
        }

    }

    //  job status change active-to-inactive-to-active
    public function change_status($id=null)
    {
        $job = JobPost::where('id',$id)->first();
        $user = User::where('id',$job->user_id)->first();

        $status = $job->status == 1 ? 0 : 1;
        JobPost::where('id',$id)->update(['status'=>$status]);

        if($status == 1){
//            try {
//                $message = get_static_option('job_approve_email_message') ?? __('Your job has been activate.');
//                $message = str_replace(["@name","@job_id"],[$user->first_name.' '.$user->last_name, $id], $message);
//                Mail::to($user->email)->send(new BasicMail([
//                    'subject' => get_static_option('job_approve_email_subject') ?? __('Job Activate Email'),
//                    'message' => $message
//                ]));
//            }
//            catch (\Exception $e) {}
            client_notification($id, $job->user_id, 'Job', __('Your job has been activate'));
            return back()->with(toastr_success(__('Job Status Successfully Changed')));
        }else{
//            try {
//                $message = get_static_option('job_decline_email_message') ?? __('Your job has been rejected.');
//                $message = str_replace(["@name","@job_id"],[$user->first_name.' '.$user->last_name, $id], $message);
//                Mail::to($user->email)->send(new BasicMail([
//                    'subject' => get_static_option('job_decline_email_subject') ?? __('Job Reject Email'),
//                    'message' => $message
//                ]));
//            }
//            catch (\Exception $e) {}
            client_notification($id, $job->user_id, 'Job', __('Your job has been rejected'));
            return back()->with(toastr_success(__('Job Successfully Rejected')));
        }
    }

    //  job status change active-to-inactive-to-active
    public function reject_job($id=null)
    {
        $job = JobPost::where('id',$id)->first();
        $user = User::where('id',$job->user_id)->first();
        // job_approve_request=2 means user must have edit the job and resubmit for activate.
        JobPost::where('id',$id)->update(['status'=>2,'job_approve_request'=>2]);
        $job_id_from_job_history_table = JobHistory::where('job_id', $id)->first();

        if(empty($job_id_from_job_history_table)){
            JobHistory::Create([
                'job_id'=>$job->id,
                'user_id'=>$job->user_id,
                'reject_count'=>1,
                'edit_count'=>0,
            ]);
        }else{
            JobHistory::where('job_id',$id)->update([
                'reject_count'=>$job_id_from_job_history_table->reject_count + 1
            ]);
        }

        try {
            $message = get_static_option('job_decline_email_message') ?? __('Your job has been rejected.');
            $message = str_replace(["@name","@job_id"],[$user->first_name.' '.$user->last_name, $id], $message);
            Mail::to($user->email)->send(new BasicMail([
                'subject' => get_static_option('job_decline_email_subject') ?? __('Job Reject Email'),
                'message' => $message
            ]));
        }catch (\Exception $e) {}

        return back()->with(toastr_success(__('Job Successfully Rejected')));
    }

    // delete single job
    public function delete_job($id)
    {
        JobHistory::where('job_id',$id)->delete();
        AdminNotification::where('identity',$id)->delete();
        Bookmark::where('identity',$id)->where('is_project_job','job')->delete();
        JobPost::find($id)->delete();
        return redirect()->back()->with(toastr_error(__('Job Successfully Deleted.')));
    }

    //auto approval settings
    public function auto_approval_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['job_auto_approval' => 'required']);
            $all_fields = ['job_auto_approval'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Auto Approval Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.job.job-auto-approval-settings');
    }
}
