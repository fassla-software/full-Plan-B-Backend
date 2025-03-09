<?php

namespace App\Http\Controllers\Api\Freelancer;

use App\Enums\MachineType;
use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobProposal;
use App\Models\NewProposal;
use App\Models\Order;
use App\Models\User;
use App\Models\HeavyEquipmentJob;
use App\Models\HeavyEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Chat\Entities\Offer;
use Modules\Subscription\Entities\UserSubscription;
use App\Traits\ApiResponseTrait;

class JobController extends Controller
{
    use ApiResponseTrait;
    public function all_job()
    {
        $jobs = JobPost::with('job_creator:id,first_name,last_name,username,image,country_id,state_id,city_id,created_at,user_verified_status', 'job_skills')
            ->withCount('job_proposals')
            ->where('on_off', '1')
            ->where('status', '1')
            ->where('job_approve_request', '1')
            ->where('type', 'fixed')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $arr = [];
        foreach ($jobs as $key => $job) {
            $arr = $job->job_creator?->user_country?->country;
        }


        if ($jobs) {
            return response()->json([
                'jobs' => $jobs,
            ]);
        }
        return response()->json(['msg' => __('no jobs found.')]);
    }

    public function job_details($id = null)
    {

        $job_details = JobPost::with(['job_creator:id,first_name,last_name,username,image,country_id,state_id,city_id,created_at,user_verified_status', 'job_skills', 'job_proposals'])
            ->where('id', $id)
            ->first();
        $user = User::select('id', 'first_name', 'last_name', 'username', 'image', 'country_id', 'state_id', 'city_id', 'created_at', 'user_verified_status', 'load_from')
            ->with('user_country')
            ->withCount('user_jobs')
            ->where('id', $job_details->user_id)->first();

        $total_job = JobPost::where('user_id', $job_details->user_id)->count();
        $total_order = Order::where('user_id', $job_details->user_id)
            ->where('status', 3)
            ->count();

        $hiring_rate = '';
        if ($hiring_rate > 0) {
            $hiring_rate = ($total_order * 100) / $total_job;
        }

        //check proposal send or not
        $check_proposal_send_or_not = 0;
        if (auth('sanctum')->check()) {
            $freelancer_id = auth('sanctum')->user()->id;
            $check_proposal_send_or_not = JobProposal::where('freelancer_id', $freelancer_id)->where('job_id', $id)->count();
        }

        if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            if ($job_details->attachment) {
                $job_details->cloud_link = render_frontend_cloud_image_if_module_exists('jobs/' . $job_details->attachment, load_from: $job_details->load_from);
            } else {
                $job_details->cloud_link = null;
            }

            if ($user->image) {
                $user->cloud_link = render_frontend_cloud_image_if_module_exists('profile/' . $user->image, load_from: $user->load_from);
            } else {
                $user->cloud_link = null;
            }
        }

        if (! file_exists(base_path('../assets/uploads/jobs/' . $job_details->attachment))) {
            $job_details->attachment = '';
        }

        if ($job_details) {
            return response()->json([
                'job_details' => $job_details,
                'user' => $user,
                'image' => asset('assets/uploads/profile/' . $user?->image),
                'job_file_path' => asset('assets/uploads/jobs/'),
                'hiring_rate' => $hiring_rate,
                'check_proposal_send_or_not' => $check_proposal_send_or_not,
                'storage_driver' => Storage::getDefaultDriver() ?? '',
            ]);
        }
        return response()->json(['msg' => __('no job found.')]);
    }

    //job proposal
    public function job_proposal_send(Request $request)
    {
        $request->validate([
            'job_id' => 'required',
            'client_id' => 'required',
            'amount' => 'required|numeric|gt:0',
            'duration' => 'required',
            'revision' => 'required|integer|min:0|max:100',
            'cover_letter' => 'required|min:100|max:1000',
            'attachment' => 'nullable|mimes:png,jpg,jpeg,bmp,gif,tiff,svg,csv,txt,xlx,xls,pdf,docx|max:2048',
        ]);

        $freelancer_id = auth('sanctum')->user()->id;
        $check_freelancer_proposal = JobProposal::where('freelancer_id', $freelancer_id)->where('job_id', $request->job_id)->first();
        if ($check_freelancer_proposal) {
            return response()->json([
                'msg' => __('You can not send one more proposal.')
            ])->setStatusCode(422);
        }

        $total_limit = UserSubscription::where('user_id', $freelancer_id)->where('payment_status', 'complete')->whereDate('expire_date', '>', Carbon::now())->sum('limit');

        if (auth('sanctum')->user()->is_suspend == 1) {
            return response()->json([
                'msg' => __('You can not send job proposal because your account is suspended. please try to contact admin.')
            ])->setStatusCode(422);
        }

        if (get_static_option('subscription_enable_disable') != 'disable') {
            $freelancer_subscription = UserSubscription::select(['id', 'user_id', 'limit', 'expire_date', 'created_at'])
                ->where('payment_status', 'complete')
                ->where('status', 1)
                ->where('user_id', $freelancer_id)
                ->where("limit", '>=', get_static_option('limit_settings'))
                ->whereDate('expire_date', '>', Carbon::now())->first();

            if ($total_limit >= get_static_option('limit_settings') ?? 2 && !empty($freelancer_subscription)) {

                $attachment_name = '';
                $upload_folder = 'jobs/proposal';
                $storage_driver = Storage::getDefaultDriver();
                $extensions = array('png', 'jpg', 'jpeg', 'bmp', 'gif', 'tiff', 'svg');

                if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                    if ($attachment = $request->file('attachment')) {
                        $attachment_name = time() . '-' . uniqid() . '.' . $attachment->getClientOriginalExtension();
                        if (in_array($attachment->getClientOriginalExtension(), $extensions)) {
                            add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachment_name, 'public');
                        } else {
                            add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachment_name, 'public');
                        }
                    }
                } else {
                    if ($attachment = $request->file('attachment')) {
                        $attachment_name = time() . '-' . uniqid() . '.' . $attachment->getClientOriginalExtension();
                        $attachment->move('assets/uploads/jobs/proposal', $attachment_name);
                    }
                }

                $proposal = JobProposal::create([
                    'job_id' => $request->job_id,
                    'freelancer_id' => auth('sanctum')->user()->id,
                    'client_id' => $request->client_id,
                    'amount' => $request->amount,
                    'duration' => $request->duration,
                    'revision' => $request->revision,
                    'cover_letter' => $request->cover_letter,
                    'attachment' => $attachment_name,
                    'load_from' => in_array($storage_driver, ['CustomUploader']) ? 0 : 1,
                ]);

                client_notification($proposal->id, $request->client_id, 'Proposal', __('You have a new job proposal'));

                UserSubscription::where('id', $freelancer_subscription->id)->update([
                    'limit' => $freelancer_subscription->limit - (get_static_option('limit_settings') ?? 2)
                ]);

                return response()->json(['msg' => __('Proposal successfully send')]);
            }
            return response()->json(['msg' => __('You have not enough connect to apply.')]);
        } else {
            $attachment_name = '';
            $upload_folder = 'jobs/proposal';
            $storage_driver = Storage::getDefaultDriver();
            $extensions = array('png', 'jpg', 'jpeg', 'bmp', 'gif', 'tiff', 'svg');

            if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
                if ($attachment = $request->file('attachment')) {
                    $attachment_name = time() . '-' . uniqid() . '.' . $attachment->getClientOriginalExtension();
                    if (in_array($attachment->getClientOriginalExtension(), $extensions)) {
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachment_name, 'public');
                    } else {
                        add_frontend_cloud_image_if_module_exists($upload_folder, $attachment, $attachment_name, 'public');
                    }
                }
            } else {
                if ($attachment = $request->file('attachment')) {
                    $attachment_name = time() . '-' . uniqid() . '.' . $attachment->getClientOriginalExtension();
                    $attachment->move('assets/uploads/jobs/proposal', $attachment_name);
                }
            }

            $proposal = JobProposal::create([
                'job_id' => $request->job_id,
                'freelancer_id' => auth()->user()->id,
                'client_id' => $request->client_id,
                'amount' => $request->amount,
                'duration' => $request->duration,
                'revision' => $request->revision,
                'cover_letter' => $request->cover_letter,
                'attachment' => $attachment_name,
                'load_from' => in_array($storage_driver, ['CustomUploader']) ? 0 : 1,
            ]);
            client_notification($proposal->id, $request->client_id, 'Proposal', __('You have a new job proposal'));
            return response()->json(['msg' => __('Proposal successfully send')]);
        }
    }
    public function jobs_filter(Request $request)
    {
        $user = auth('sanctum')?->user();
        $allJobs = collect();
        foreach (MachineType::values() as $serviceType) {
            $serviceModel = $this->getModelClassFromServiceType($serviceType); // equipment name
            $jobModel = $this->getJobModelClassFromServiceType($serviceType);   // service type jop

            // Check if both service and job models exist
            if (class_exists($serviceModel) && class_exists($jobModel)) {
                // Fetch all user-owned services
                $userServices = $serviceModel::where('user_id', $user?->id)->get();

                if (!$userServices->isEmpty()) {
                    $subCategoryIds = $userServices->pluck('sub_category_id')->unique();

                    // Loop through each user's service
                    foreach ($userServices as $equipment) {
                        $equipmentLat = $equipment->lat;
                        $equipmentLong = $equipment->long;

                        // Fetch jobs related to those services, excluding the user's own jobs
                        $jobs = $jobModel::with('subCategory')
                            ->whereIn('sub_category_id', $subCategoryIds)
                            ->where('user_id', '!=', $user?->id)
                            ->get();

                        // Manually filter jobs by distance
                        $filteredJobs = $jobs->filter(function ($job) use ($equipmentLat, $equipmentLong) {
                            $distance = $this->calculateDistance(
                                $equipmentLat,
                                $equipmentLong,
                                $job->lat,
                                $job->long
                            );

                            return $distance <= (float) $job->search_radius;
                        });

                        $allJobs = $allJobs->merge($filteredJobs);
                    }
                }
            }
        }

        return $this->successResponse($allJobs, 'All related requests fetched successfully.', 200);
    }

    /**
     * Calculate distance between two points using the Haversine formula.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius of the earth in km

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $earthRadius * $angle; // Distance in km
    }
    //job filter
    //    public function jobs_filter(Request $request)
    //    {
    //
    //      	/*$user = auth('sanctum')->user();
    //        $allJobs = collect(); // Collection to store all jobs dynamically
    //
    //        foreach (MachineType::values() as $serviceType) {
    //            $serviceModel = $this->getModelClassFromServiceType($serviceType);
    //            $jobModel = $this->getJobModelClassFromServiceType($serviceType);
    //
    //            // Check if both service and job models exist
    //            if (class_exists($serviceModel) && class_exists($jobModel)) {
    //                // Fetch all user-owned services
    //                $userServices = $serviceModel::where('user_id', $user->id)->get();
    //
    //                if (!$userServices->isEmpty()) {
    //                    $subCategoryIds = $userServices->pluck('sub_category_id')->unique();
    //
    //                    // Fetch jobs related to those services, excluding the user's own jobs
    //                    $jobs = $jobModel::with('subCategory')
    //                        ->whereIn('sub_category_id', $subCategoryIds)
    //                        ->where('user_id', '!=', $user->id)
    //                        ->get();
    //
    //                    $allJobs = $allJobs->merge($jobs);
    //                }
    //            }
    //        }
    //
    //        return $this->successResponse($allJobs, 'All related jobs fetched successfully', 200);*/
    //
    //        /*$jobs = JobPost::with('job_creator:id,first_name,last_name,username,image,country_id,state_id,city_id,created_at,user_verified_status','job_skills','job_sub_categories')
    //            ->withCount('job_proposals')
    //            ->where('on_off','1')
    //            ->where('status','1')
    //            ->where('job_approve_request','1')
    //            ->latest();
    //
    //        if(!empty($request->country) || !empty($request->type) || !empty($request->level) || !empty($request->min_price) || !empty($request->max_price) || !empty($request->duration || !empty($request->category) || !empty($request->subcategory) || !empty($request->string) )){
    //            if(!empty($request->country)){
    //
    //                $jobs = $jobs->WhereHas('job_creator',function($q) use($request){
    //                    $q->where('country_id',$request->country);
    //                });
    //            }
    //
    //            if(!empty($request->type)){
    //                $jobs = $jobs->where('type',$request->type);
    //            }
    //
    //            if(!empty($request->level)){
    //                $jobs = $jobs->where('level',$request->level);
    //            }
    //
    //            if(!empty($request->min_price) && !empty($request->max_price)){
    //                $jobs = $jobs->whereBetween('budget',[$request->min_price,$request->max_price]);
    //            }
    //
    //            if(!empty($request->duration)){
    //                $jobs = $jobs->where('duration',$request->duration);
    //            }
    //
    //            if(!empty($request->category)){
    //                $jobs = $jobs->where('category',$request->category);
    //            }
    //
    //            if(!empty($request->subcategory)){
    //                $jobs = $jobs->WhereHas('job_sub_categories',function($q) use($request){
    //                    $q->where('sub_categories.id',$request->subcategory);
    //                });
    //            }
    //
    //            if(!empty($request->string)){
    //                $jobs = $jobs->where('title','LIKE','%'.$request->string.'%');
    //            }
    //        }
    //
    //        $jobs = $jobs->paginate(10)->withQueryString();
    //
    //        $arr = [];
    //        foreach($jobs as $key=> $job){
    //            $arr = $job->job_creator?->user_country?->country;
    //        }
    //
    //        if($jobs->total() > 0){
    //            return response()->json([
    //                'jobs' => $jobs,
    //            ]);
    //        }else{
    //            return response()->json(['msg' => __('no jobs found.')]);
    //        }*/
    //    }

    /**
     * Dynamically resolve the model class based on the service type.
     */
    private function getModelClassFromServiceType($serviceType)
    {
        $modelName = Str::studly($serviceType); // Convert to StudlyCase
        return "App\\Models\\$modelName";
    }

    /**
     * Dynamically resolve the job model class based on the service type.
     */
    private function getJobModelClassFromServiceType($serviceType)
    {
        $modelName = Str::studly($serviceType) . 'Job'; // Add 'Job' suffix for job models
        return "App\\Models\\$modelName";
    }

    //my proposals
    public function my_proposal()
    {
        $my_proposals = NewProposal::with(['request.requestable.subCategory']) // Eager load related data
            ->where('user_id', auth('sanctum')->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Transform the response to only include the sub_category_image
        $my_proposals->getCollection()->transform(function ($proposal) {
            $request = $proposal->request;

            if ($request && $request->requestable) {
                $subCategory = $request->requestable->subCategory;

                // Attach the sub_category_image only
                if ($subCategory && $subCategory->image) {
                    $proposal->sub_category_image = $this->getFullImageUrl($subCategory->image);
                } else {
                    $proposal->sub_category_image = $this->getDefaultImageUrl();
                }
            } else {
                $proposal->sub_category_image = $this->getDefaultImageUrl();
            }

            // Remove the request relationship from the response
            unset($proposal->request);

            return $proposal;
        });

        // Return the paginated response
        return $this->paginatedResponse($my_proposals, 'All proposals fetched successfully.', 200);
    }



    private function getFullImageUrl($imageId)
    {
        if (!$imageId) {
            return null;
        }
        $imageDetails = get_attachment_image_by_id($imageId);
        return $imageDetails['img_url'] ?? null;
    }

    private function getDefaultImageUrl()
    {
        return asset('assets/uploads/no-image.png');
    }

    public function my_offer()
    {
        $my_offers = Offer::with('client:id,first_name,last_name,image,load_from')->where('freelancer_id', auth('sanctum')->user()->id)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if (cloudStorageExist() && in_array(Storage::getDefaultDriver(), ['s3', 'cloudFlareR2', 'wasabi'])) {
            $my_offers->transform(function ($offer) {
                $offer->client->cloud_link = render_frontend_cloud_image_if_module_exists('profile/' . $offer?->client->image, load_from: $offer?->client->load_from);
                return $offer;
            });
        }

        return response()->json([
            'my_offers' => $my_offers,
            'profile_image_path' => asset('assets/uploads/profile/'),
            'storage_driver' => Storage::getDefaultDriver() ?? '',
        ]);
    }
}
