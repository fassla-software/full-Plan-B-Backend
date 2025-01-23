<?php

namespace App\Http\Controllers\Backend;

use App\Helper\LogActivity;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\AdminNotification;
use App\Models\Bookmark;
use App\Models\Project;
use App\Models\ProjectAttribute;
use App\Models\ProjectHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProjectController extends Controller
{
    // all projects
    public function all_project()
    {
        $all_projects = Project::whereHas('project_creator')->latest()->paginate(10);
        return view('backend.pages.project.all-project',compact('all_projects'));
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
            $project = Project::with('project_history')->whereHas('project_creator')->where('id',$id)->first();
            if($project){
                $user = User::with('user_introduction','user_country','user_state','user_city')->where('id',$project->user_id)->first();
                AdminNotification::where('identity',$id)->update(['is_read'=>1]);
                return isset($project) ? view('backend.pages.project.project-details',compact(['project','user'])) : back();
            }
            return back();
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
    public function delete_project($id)
    {
        ProjectAttribute::where('create_project_id',$id)->delete();
        ProjectHistory::where('project_id',$id)->delete();
        Bookmark::where('identity',$id)->where('is_project_job','project')->delete();
        Project::find($id)->delete();

        //security manage
        if(moduleExists('SecurityManage')){
            LogActivity::addToLog('Project delete by admin','Admin');
        }
        return redirect()->back()->with(toastr_error(__('Project Successfully Deleted with Attributes.')));
    }
}
