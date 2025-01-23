<?php

namespace App\Http\Middleware;

use App\AdminCommission;
use App\Helpers\HomePageStaticSettings;
use App\Helpers\LanguageHelper;
use App\Models\Language;
use App\Models\Menu;
use App\Models\StaticOption;
use App\SocialIcon;
use Closure;
use Illuminate\Support\Facades\Request;
use Modules\Pages\Entities\Page;

class GlobalVariableMiddleware
{

    public function handle($request, Closure $next)
    {
//        $lang = LanguageHelper::user_lang_slug();
        $all_language = Language::where('status', 'publish')->get();

        $primary_menu = Menu::where(['status' => 'default'])->first();
        if(Request::is('home/01')){
            $home_variant_number = '01';
        }
        elseif(Request::is('home/02')){
            $home_variant_number = '02';
        }
        elseif(Request::is('home/03')){
            $home_variant_number = '03';
        }
        elseif(Request::is('home/04')){
            $home_variant_number = '04';
        }else{
            $home_variant_number = get_static_option('home_page_variant');
        }

        //make a function to call all static option by home page
        $static_option_arr = [
            'site_white_logo',
            'site_google_analytics',
            'og_meta_image_for_site',
            'site_main_color_one',
            'site_main_color_two',
            'site_secondary_color',
            'site_heading_color',
            'site_paragraph_color',
            'heading_font',
            'heading_font_family',
            'body_font_family',
            'body_font_family',
            'site_rtl_enabled',
            'services_page_slug',
            'about_page_slug',
            'contact_page_slug',
            'blog_page_slug',
            'team_page_slug',
            'faq_page_slug',
            'works_page_slug',
            'site_third_party_tracking_code',
            'site_favicon',
            'home_page_variant',
            'item_license_status',
            'site_script_unique_key',
        ];
        $static_field_data = StaticOption::whereIn('option_name', $static_option_arr)->get()->mapWithKeys(function ($item) {
            return [$item->option_name => $item->option_value];
        })->toArray();

        $navbar_number = Page::where('id',16)->first();

        view()->share([
            'global_static_field_data' => $static_field_data,
            'home_variant_number' => $home_variant_number,
            'primary_menu' => optional($primary_menu)->id,
            'navbar_number' => $navbar_number,
        ]);


        return $next($request);
    }
}
