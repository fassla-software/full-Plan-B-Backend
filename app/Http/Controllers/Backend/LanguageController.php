<?php

namespace App\Http\Controllers\Backend;

use App\BlogCategory;
use App\Blog;
use App\Contribution;
use App\Counterup;
use App\Event;
use App\EventCategory;
use App\Gallery;
use App\GalleryCategory;
use App\HeaderSlider;
use App\Http\Controllers\Controller;
use App\KeyFeatures;

use App\Models\Language;
use App\Models\StaticOption;
use App\Newsletter;
use App\Page;
use App\Tag;
use App\Testimonial;
use App\TopbarInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class LanguageController extends Controller
{

    public function all_language()
    {
        $all_lang = Language::all();
        return view('backend.pages.languages.all-language',compact('all_lang'));
    }

    public function add_language(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string:max:191',
            'direction' => 'required|string:max:191',
            'slug' => 'required|string:max:191|unique:languages,slug',
            'status' => 'required|string:max:191',
        ]);

        Language::create([
            'name' => $request->name,
            'direction' => $request->direction,
            'slug' => $request->slug,
            'status' => $request->status,
            'default' => 0
        ]);

        //generate admin panel string
        $backend_default_lang_data = file_get_contents(resource_path('lang/') . 'default.json');
        file_put_contents(resource_path('lang/') . $request->slug . '.json', $backend_default_lang_data);

        return redirect()->back()->with(toastr_success(__('New Language Successfully Added.')));
    }

    public function update_language(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string:max:191',
            'direction' => 'required|string:max:191',
            'status' => 'required|string:max:191',
            'slug' => 'required|string:max:191'
        ]);
        Language::where('id', $request->id)->update([
            'name' => $request->name,
            'direction' => $request->direction,
            'status' => $request->status,
            'slug' => $request->slug
        ]);
        $backend_lang_file_path = resource_path('lang/') . $request->slug . '.json';

        if (!file_exists($backend_lang_file_path) && !is_dir($backend_lang_file_path)) {
            file_put_contents(resource_path('lang/') . $request->slug . '.json', file_get_contents(resource_path('lang/') . 'default.json'));
        }
        return redirect()->back()->with(toastr_success(__('Language Successfully Updated.')));
    }

    public function all_edit_words($slug)
    {
        $backend_lang_file_path = resource_path('lang/') . $slug . '.json';
        if (!file_exists($backend_lang_file_path) && !is_dir($backend_lang_file_path)) {
            file_put_contents(resource_path('lang/') . $slug . '.json', file_get_contents(resource_path('lang/') . 'default.json'));
        }

        $all_word = file_get_contents(resource_path('lang/') . $slug . '.json');

        return view('backend.pages.languages.edit-words')->with([
            'all_word' => json_decode($all_word),
            'lang_slug' => $slug,
            'type' => 'backend',
            'language' => Language::where('slug',$slug)->first()
        ]);
    }

    public function update_words(Request $request, $slug)
    {
        $this->validate($request,[
           'string_key' => 'required',
           'translate_word' => 'required',
        ],[
            'string_key.required' => __('select source text'),
            'translate_word.required' => __('add translate text'),
        ]);
        // get text json file
        // get current key index and replace it in the json file
        if (file_exists(resource_path('lang/') . $slug . '.json')) {
            $default_lang_data = file_get_contents(resource_path('lang') . '/'.$slug.'.json');
            $default_lang_data = (array)json_decode($default_lang_data);
            $default_lang_data[$request->string_key] = $request->translate_word;
            $default_lang_data = (object)$default_lang_data;
            $default_lang_data = json_encode($default_lang_data);
            file_put_contents(resource_path('lang/') . $slug . '.json', $default_lang_data);
        }
        return redirect()->back()->with(toastr_success(__('Words Change Success')));
    }

    public function regenerate_source_text(Request $request){
        //
        $this->validate($request,[
            'slug' => 'required'
        ]);
        if (file_exists(resource_path('lang/') . $request->slug . '.json')){
            @unlink(resource_path('lang/') . $request->slug . '.json');
        }
        Artisan::call('translatable:export '.$request->slug);
        return redirect()->back()->with(toastr_success(__('Source text generate success')));
    }

    public function add_new_words(Request $request)
    {
        $this->validate($request, [
            'lang_slug' => 'required|string',
            'new_string' => 'required|string',
            'translate_string' => 'required|string',
        ]);
        if (file_exists(resource_path('lang/') . $request->lang_slug . '.json')) {
             $default_lang_data = file_get_contents(resource_path('lang') . '/'.$request->lang_slug.'.json');
            $default_lang_data = (array)json_decode($default_lang_data);
            $default_lang_data[$request->new_string] = $request->translate_string;
            $default_lang_data = (object)$default_lang_data;
            $default_lang_data = json_encode($default_lang_data);
            file_put_contents(resource_path('lang/') . $request->lang_slug . '.json', $default_lang_data);
        }
        return redirect()->back()->with(toastr_success(__('New Word Successfully Added.')));
    }

    public function make_default(Request $request, $id)
    {
        Language::where('default', 1)->update(['default' => 0]);
        Language::find($id)->update(['default' => 1]);
        $lang = Language::find($id);
        return redirect()->back()->with(toastr_success(__('Default language set to').' '.$lang->name));
    }

}
