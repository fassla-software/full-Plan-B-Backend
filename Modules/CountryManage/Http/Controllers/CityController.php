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

class CityController extends Controller
{
    // display all city and add new city
    public function all_city(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'country'=> 'required',
                'state'=> 'required',
                'city'=> 'required|unique:cities|max:191',
            ]);
            City::create([
                'city' => $request->city,
                'country_id' => $request->country,
                'state_id' => $request->state,
                'status' => $request->status,
            ]);
            toastr_success(__('New City Successfully Added'));
        }
        $all_countries = Country::all_countries();
        $all_states = State::all_states();

        $all_cities = City::latest()->paginate(10);
        return view('countrymanage::city.all-city',compact('all_states','all_countries','all_cities'));
    }

    // edit city
    public function edit_city(Request $request)
    {
        $request->validate([
            'city'=> 'required|max:191|unique:cities,city,'.$request->city_id,
            'country'=> 'required',
            'state'=> 'required',
        ]);
        City::where('id',$request->city_id)->update([
            'city'=>$request->city,
            'state_id'=>$request->state,
            'country_id'=>$request->country,
        ]);
        return redirect()->back()->with(toastr_success(__('City Successfully Updated')));
    }

    // change status
    public function city_status($id)
    {
        $city = City::select('status')->where('id',$id)->first();
        $city->status==1 ? $status=0 : $status=1;
        City::where('id',$id)->update(['status'=>$status]);
        return redirect()->back()->with(toastr_success(__('Status Successfully Changed')));
    }

    // delete single city
    public function delete_city($id)
    {
        City::find($id)->delete();
        return redirect()->back()->with(toastr_error(__('City Successfully Deleted')));
    }

    // delete multi city
    public function bulk_action_city(Request $request){
        City::whereIn('id',$request->ids)->delete();
        return redirect()->back()->with(toastr_success(__('Selected City Successfully Deleted')));
    }

    // import settings
    public function import_settings()
    {
        return view('countrymanage::city.import-city');
    }

    // import settings update
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
                return view('countrymanage::city.import-city', [
                    'import_data' => $csv_data,
                ]);
            }

        }
        toastr_error(__('something went wrong try again!'));
        return back();
    }

    // import city to database
    public function import_to_database_settings(Request $request)
    {
        $request->validate([
            'city' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
        ]);

        $file_tmp_name = Session::get('import_csv_file_name');
        $data = array_map('str_getcsv', file('assets/uploads/import/' . $file_tmp_name));

        $csv_data = current(array_slice($data, 0, 1));
        $csv_data = array_map(function ($item) {
            return trim($item);
        }, $csv_data);

        $imported_cities = 0;
        $x = 0;
        $city = array_search($request->city, $csv_data, true);

        foreach ($data as $index => $item) {
            if($x == 0){
                $x++;
                continue ;
            }
            if ($index === 0) {
                continue;
            }
            if (empty($item[$city])){
                continue;
            }

            $find_city = City::where('city', $item[$city])
                ->where('country_id', $request->country_id)
                ->where('state_id', $request->state_id)
                ->count();

            if ($find_city < 1) {
                $city_data = [
                    'city' => $item[$city] ?? '',
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'status' => $request->status,
                ];
            }
            if ($find_city < 1) {
                City::create($city_data);
                $imported_cities++;
            }
        }
        toastr_success($imported_cities.' '. __('Cities imported successfully'));
        return redirect()->route('admin.city.import.csv.settings');
    }

    // pagination
    function pagination(Request $request)
    {
        if($request->ajax()){
            $all_cities = City::latest()->paginate(10);
            return view('countrymanage::city.search-result', compact('all_cities'))->render();
        }
    }

    // search city
    public function search_city(Request $request)
    {
        $all_cities= City::where('city', 'LIKE', "%". strip_tags($request->string_search) ."%")
            ->paginate(10);
        if($all_cities->total() >= 1){
            return view('countrymanage::city.search-result', compact('all_cities'))->render();
        }else{
            return response()->json([
                'status'=>__('nothing')
            ]);
        }
    }

}
