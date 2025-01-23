<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Modules\NewsLetter\Entities\NewsLetter;

class UserReportController extends Controller
{
    public function all_reports()
    {
        $all_reports = Report::latest()->paginate(10);
        return view ('backend.pages.reports.all-reports',compact('all_reports'));
    }

    public function report_update(Request $request)
    {
        $request->validate([
            'status' => 'required',
            'note' => 'required'
        ]);

        Report::where('id',$request->report_id)->update([
            'status' => $request->status,
            'note' => $request->note,
        ]);

        $report = Report::where('id',$request->report_id)->first();

        if($request->status == 0){
            $status_text = __('in review');
        }
        if($request->status == 1){
            $status_text = __('closed');
        }
        if($request->status == 2){
            $status_text = __('rejected');
        }

        if($report->reporter == 'client'){
            client_notification($request->report_id,$report->client_id,'Report', __('Your report status changed to') .' '. $status_text);
        }
        if($report->reporter == 'freelancer'){
            freelancer_notification($request->report_id,$report->freelancer_id,'Report', __('Your report status changed to') .' '. $status_text);
        }
        return back()->with(toastr_success(__('Status Successfully Updated')));
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_reports = Report::latest()->paginate(10);
            return view('backend.pages.reports.search-result',compact('all_reports'))->render();
        }
    }
}
