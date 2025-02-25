<?php

namespace App\Http\Controllers\Backend;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\Bookmark;
use App\Models\CraneRental;
use App\Models\HeavyEquipment;
use App\Models\Project;
use App\Models\ProjectAttribute;
use App\Models\ProjectHistory;
use App\Models\User;
use App\Models\VehicleRental;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use App\Exports\EquipmentExport;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    // all projects
public function all_project(Request $request)
{
    $equipmentModels = [
        HeavyEquipment::class,
        VehicleRental::class,
        CraneRental::class,
    ];

    $perPage = $request->get('per_page', 10);
    $query = collect();

    foreach ($equipmentModels as $model) {
        // Eager load relations: user, category, subCategory
        $equipmentList = $model::with(['user:id,first_name,last_name', 'category:id,category', 'subCategory:id,image'])
            ->whereNotNull('id')
            ->get()
            ->map(function ($equipment) {
                $filteredEquipment = collect($equipment)->filter(function ($value) {
                    return !is_null($value);
                });

                // Attach sub-category image
                $filteredEquipment['sub_category_image'] = $equipment->subCategory && $equipment->subCategory->image
                    ? $this->getFullImageUrl($equipment->subCategory->image)
                    : $this->getDefaultImageUrl();

                // Add user name
                $filteredEquipment['user_name'] = $equipment->user ? $equipment->user->first_name . ' ' . $equipment->user->last_name  : 'N/A';

                // Add category name
                $filteredEquipment['category_name'] = $equipment->category ? $equipment->category->category : 'N/A';

                // Add created_at
                $filteredEquipment['created_at'] = $equipment->created_at->format('Y-m-d H:i:s');

                // Remove the subCategory relation
                $filteredEquipment->forget('subCategory');

                return $filteredEquipment->isNotEmpty() ? $filteredEquipment : null;
            })
            ->filter();

        $query = $query->merge($equipmentList);
    }

    // Paginate the results
    $page = $request->get('page', 1);
    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
        $query->forPage($page, $perPage),
        $query->count(),
        $perPage,
        $page,
        ['path' => url()->current(), 'query' => $request->query()]
    );

    return view('backend.pages.project.all-project', compact('paginated'));
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

    //auto approval settings
    public function auto_approval_settings(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate(['project_auto_approval' => 'required']);
            $all_fields = ['project_auto_approval'];

            foreach ($all_fields as $field) {
                update_static_option($field, $request->$field);
            }
            toastr_success(__('Auto Approval Settings Updated Successfully.'));
            return back();
        }
        return view('backend.pages.project.project-auto-approval-settings');
    }

    // search project
    public function search_project(Request $request)
    {
        $all_projects= Project::whereHas('project_creator')->where('title', 'LIKE', "%". strip_tags($request->string_search) ."%")->paginate(10);
        return $all_projects->total() >= 1 ? view('backend.pages.project.search-result', compact('all_projects'))->render() : response()->json(['status'=>__('nothing')]);
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_projects = Project::whereHas('project_creator')->latest()->paginate(10);
            return view('backend.pages.project.search-result', compact('all_projects'))->render();
        }
    }

    //  project details
    public function project_details($id=null)
    {
          // Find the equipment across models
    $equipmentModels = [
        HeavyEquipment::class,
        VehicleRental::class,
        CraneRental::class,
    ];

    $equipment = null;

    foreach ($equipmentModels as $model) {
        $equipment = $model::with(['user', 'category', 'subCategory'])
            ->where('id', $id)
            ->first();

        if ($equipment) break; // Stop looping if found
    }

    // Handle if no equipment found
    if (!$equipment) {
        return back()->withErrors(['error' => 'Equipment not found']);
    }

    // Load user details with relations
    $user = User::with(['user_introduction', 'user_country', 'user_state', 'user_city'])
        ->where('id', $equipment->user_id)
        ->first();

    // Attach sub-category image
    $equipment->sub_category_image = $equipment->subCategory && $equipment->subCategory->image
        ? $this->getFullImageUrl($equipment->subCategory->image)
        : $this->getDefaultImageUrl();

    return view('backend.pages.project.project-details', compact('equipment', 'user'));
      
            /*$project = Project::with('project_history')->whereHas('project_creator')->where('id',$id)->first();
            if($project){
                $user = User::with('user_introduction','user_country','user_state','user_city')->where('id',$project->user_id)->first();
                AdminNotification::where('identity',$id)->update(['is_read'=>1]);
                return isset($project) ? view('backend.pages.project.project-details',compact(['project','user'])) : back();
            }
            return back();*/
    }

    public function export_equipment(Request $request)
    {
        $equipmentModels = [
            HeavyEquipment::class,
            VehicleRental::class,
            CraneRental::class,
        ];

        $allEquipments = collect();

        foreach ($equipmentModels as $model) {
            $equipmentList = $model::with(['user:id,first_name,last_name', 'category:id,category', 'subCategory:id,image'])
                ->get()
                ->map(function ($equipment) {
                    return [
                        'id' => $equipment->id,
                        'name' => $equipment->name ?? 'N/A',
                        'user_name' => $equipment->user ? $equipment->user->first_name . ' ' . $equipment->user->last_name : 'N/A',
                        'category_name' => $equipment->category ? $equipment->category->category : 'N/A',
                        'model' => $equipment->model,
                        'created_at' => $equipment->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            $allEquipments = $allEquipments->merge($equipmentList);
        }

        return Excel::download(new EquipmentExport($allEquipments), 'all_services.xlsx');
    }

    //  project status change active-to-inactive-to-active
    public function change_status($id=null)
    {
        $project = Project::where('id',$id)->first();
        $user = User::where('id',$project->user_id)->first();
        $status = $project->status ===0 || $project->status ===2 ? 1 : 0;
        Project::where('id',$id)->update(['status'=>$status]);

        //security manage
        if(moduleExists('SecurityManage')){
            LogActivity::addToLog('Project status change by admin','Admin');
        }

        if($status === 1){
            Project::where('id',$id)->update(['project_approve_request'=>1]);
            try {
                $message = get_static_option('project_approve_email_message') ?? __('Your project successfully activate.');
                $message = str_replace(["@name","@project_id"],[$user->first_name.' '.$user->last_name, $id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('project_approve_email_subject') ?? __('Project Activate Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}
        }else{
            // project_approve_request=2 means user will edit the project and resubmit for activate
            Project::where('id',$id)->update(['project_approve_request'=>2]);
            try {
                $message = get_static_option('project_inactivate_email_message') ?? __('Your project successfully approved.');
                $message = str_replace(["@name","@project_id"],[$user->first_name.' '.$user->last_name, $id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('project_inactivate_email_subject') ?? __('Project Inactivate Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}
        }

        return back()->with(toastr_success(__('Project Status Successfully Changed')));
    }

    //  project status change active-to-inactive-to-active
    public function reject_project(Request $request)
    {
        $project = Project::where('id',$request->reject_project_id)->first();
        $user = User::where('id',$project->user_id)->first();
        // project_approve_request=2 means user must have edit the project and resubmit for activate.
        Project::where('id',$request->reject_project_id)->update(['status'=>2,'project_approve_request'=>2]);
        $project_id_from_project_history_table = ProjectHistory::where('project_id', $request->reject_project_id)->first();

        freelancer_notification($request->reject_project_id, $project->user_id, 'Reject Project','Project rejected by admin');

        if(empty($project_id_from_project_history_table)){
            ProjectHistory::Create([
                'project_id'=>$project->id,
                'user_id'=>$project->user_id,
                'reject_count'=>1,
                'edit_count'=>0,
                'reject_reason'=>$request->reject_reason,
            ]);
        }else{
            ProjectHistory::where('project_id',$request->reject_project_id)->update([
                'reject_count'=>$project_id_from_project_history_table->reject_count + 1,
                'reject_reason'=>$request->reject_reason ?? $project_id_from_project_history_table->reject_reason,
            ]);
        }

            try {
                $message = get_static_option('project_decline_email_message') ?? __('Your project has been rejected.');
                $message = str_replace(["@name","@project_id"],[$user->first_name.' '.$user->last_name, $request->reject_project_id], $message);
                Mail::to($user->email)->send(new BasicMail([
                    'subject' => get_static_option('project_decline_email_subject') ?? __('Project Reject Email'),
                    'message' => $message
                ]));
            }catch (\Exception $e) {}

        return back()->with(toastr_success(__('Project Successfully Rejected')));
    }

    // delete single project with attributes
    public function delete_project(Request $request, $id)
    {
        // Define all equipment models
        $equipmentModels = [
            HeavyEquipment::class,
            VehicleRental::class,
            CraneRental::class,
        ];

        foreach ($equipmentModels as $model) {
            $equipment = $model::find($id);

            if ($equipment) {
                $equipment->delete();
                return response()->json(['success' => true, 'message' => 'Equipment deleted successfully!']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Equipment not found!']);

//        ProjectAttribute::where('create_project_id',$id)->delete();
//        ProjectHistory::where('project_id',$id)->delete();
//        Bookmark::where('identity',$id)->where('is_project_job','project')->delete();
//        Project::find($id)->delete();
//
//        //security manage
//        if(moduleExists('SecurityManage')){
//            LogActivity::addToLog('Project delete by admin','Admin');
//        }
//        return redirect()->back()->with(toastr_error(__('Project Successfully Deleted with Attributes.')));
    }
}
