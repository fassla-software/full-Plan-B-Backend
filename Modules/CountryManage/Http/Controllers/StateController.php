<?php

namespace Modules\CountryManage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\CountryManage\Entities\City;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class StateController extends Controller
{
    public function all_state(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'state'=> 'required|unique:states|max:191',
                'country'=> 'required',
                'timezone'=> 'required',
            ]);
            State::create([
                'state' => $request->state,
                'country_id' => $request->country,
                'timezone' => $request->timezone,
                'status' => $request->status,
            ]);
            toastr_success(__('New State Successfully Added'));
        }
        $all_states = State::latest()->paginate(10);
        $all_countries = Country::all_countries();
        return view('countrymanage::state.all-state',compact('all_states','all_countries'));
    }

    public function edit_state(Request $request)
    {
        $request->validate([
            'edit_state'=> 'required|max:191|unique:states,state,'.$request->state_id,
            'edit_country'=> 'required',
            'edit_timezone'=> 'required',
        ]);
        State::where('id',$request->state_id)->update([
            'state'=>$request->edit_state,
            'country_id'=>$request->edit_country,
            'timezone'=>$request->edit_timezone,
        ]);
        return redirect()->back()->with(toastr_success(__('State Successfully Updated')));
    }

    public function change_status_state($id)
    {
        $state = State::select('status')->where('id',$id)->first();
        $state->status==1 ? $status=0 : $status=1;
        State::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    public function delete_state($id)
    {
        State::find($id)->delete();
        return redirect()->back()->with(toastr_error(__('State Successfully Deleted')));
    }

    public function bulk_action_state(Request $request){
        State::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(toastr_success(__('Selected State Successfully Deleted')));
    }

    public function import_settings()
    {
        return view('countrymanage::state.import-state');
    }

    public function update_import_settings(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:150000'
        ]);

        //: work on file mapping
        if ($request->hasFile('csv_file')) {
            $file = $request->csv_file;
            $extenstion = $file->getClientOriginalExtension();
            if ($extenstion == 'csv') {
                //copy file to temp folder

                $old_file = Session::get('import_csv_file_name');
                if (file_exists('assets/uploads/import/' . $old_file)) {
                    @unlink('assets/uploads/import/' . $old_file);
                }
                $file_name_with_ext = $file->getClientOriginalName();

                $file_name = pathinfo($file_name_with_ext, PATHINFO_FILENAME);
                $file_name = strtolower(Str::slug($file_name));

                $file_tmp_name = $file_name . time() . '.' . $extenstion;
                $file->move('assets/uploads/import', $file_tmp_name);

                $data = array_map('str_getcsv', file('assets/uploads/import/' . $file_tmp_name));
                $csv_data = array_slice($data, 0, 1);

                Session::put('import_csv_file_name', $file_tmp_name);

                return view('countrymanage::state.import-state', [
                    'import_data' => $csv_data,
                ]);
            }

        }
        toastr_error(__('something went wrong try again!'));
        return back();
    }

    public function import_to_database_settings(Request $request)
    {
        $file_tmp_name = Session::get('import_csv_file_name');
        $data = array_map('str_getcsv', file('assets/uploads/import/' . $file_tmp_name));

        $csv_data = current(array_slice($data, 0, 1));
        $csv_data = array_map(function ($item) {
            return trim($item);
        }, $csv_data);

        $imported_states = 0;
        $x = 0;
        $state = array_search($request->state, $csv_data, true);

        foreach ($data as $index => $item) {
            if($x == 0){
                $x++;
                continue ;
            }
            if ($index === 0) {
                continue;
            }
            if (empty($item[$state])){
                continue;
            }

            $find_state = State::where('state', $item[$state])->where('country_id', $request->country_id)->count();

            if ($find_state < 1) {
                $state_data = [
                    'state' => $item[$state] ?? '',
                    'country_id' => $request->country_id,
                    'timezone' => $request->timezone,
                    'status' => $request->status,
                ];
            }
            if ($find_state < 1) {
                State::create($state_data);
                $imported_states++;
            }
        }
        toastr_success($imported_states.' '. __('States imported successfully'));
        return redirect()->route('admin.state.import.csv.settings');
    }


    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_states = State::latest()->paginate(10);
            return view('countrymanage::state.search-result', compact('all_states'))->render();
        }
    }

    // search category
    public function search_state(Request $request)
    {
        $all_states= State::where('state', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);
        if($all_states->total() >= 1){
            return view('countrymanage::state.search-result', compact('all_states'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }
}
