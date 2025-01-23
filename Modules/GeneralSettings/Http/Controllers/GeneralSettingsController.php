<?php

namespace Modules\GeneralSettings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\GeneralSettings\Http\Requests\SeoSettingsRequest;
use Modules\GeneralSettings\Http\Requests\SocialLoginRequest;
use Modules\GeneralSettings\Http\Requests\ThirdPartyScriptsRequest;
use Modules\GeneralSettings\Http\Requests\TypographySettingsRequest;
use Modules\GeneralSettings\Http\Requests\BasicSettingsRequest;
use Modules\GeneralSettings\Http\Requests\ColorSettingsRequest;
use Modules\GeneralSettings\Http\Requests\SiteIdentityRequest;
use Modules\GeneralSettings\Http\Services\GeneralSettingsService;
use Modules\Pages\Entities\Page;
use Xgenious\XgApiClient\Facades\XgApiClient;

class GeneralSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function reading(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->reading($request);
        }
        $all_pages = Page::where(['status'=>'1'])->get();
        return view('generalsettings::reading',compact('all_pages'));
    }

    public function navbar_global_variant(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->navbar_global_variant($request);
        }
        return view('generalsettings::navbar-global-variant');
    }

    public function footer_global_variant(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->footer_global_variant($request);
        }
        return view('generalsettings::footer-global-variant');
    }

    public function site_identity(SiteIdentityRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->site_identity($request);
        }
        return view('generalsettings::site-identity');
    }

    public function basic_settings(BasicSettingsRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->basic_settings($request);
        }
        return view('generalsettings::basic-settings');
    }

    public function color_settings(ColorSettingsRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->color_settings($request);
        }
        return view('generalsettings::color-settings');
    }

    public function typography_settings(TypographySettingsRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->typography_settings($request);
        }
        $google_fonts = file_get_contents('assets/frontend/webfonts/google-fonts.json');
        return view('generalsettings::typography')->with(['google_fonts' => json_decode($google_fonts)]);
    }

    public function typography_settings_single(Request $request)
    {
        $all_google_fonts = file_get_contents('assets/frontend/webfonts/google-fonts.json');
        $decoded_fonts = json_decode($all_google_fonts, true);
        return response()->json($decoded_fonts[$request->font_family]);
    }

    public function seo_settings(SeoSettingsRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->seo_settings($request);
        }
        return view('generalsettings::seo-settings');
    }


    public function third_party_scripts_settings(ThirdPartyScriptsRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->third_party_scripts_settings($request);
        }
        return view('generalsettings::third-party-scripts');
    }

    public function social_login_settings(SocialLoginRequest $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->social_login_settings($request);
        }
        return view('generalsettings::social-login');
    }

    public function email_template_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->email_template_settings($request);
        }
        return view('generalsettings::email-template-settings');
    }

    public function smtp_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->smtp_settings($request);
        }
        return view('generalsettings::smtp-settings');
    }

    public function test_smtp_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->test_smtp_settings($request);
        }
    }

    public function custom_css(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->custom_css($request);
        }
        $custom_css = '/* Write Custom Css Here */';
        if (file_exists('assets/frontend/css/dynamic-style.css')) {
            $custom_css = file_get_contents('assets/frontend/css/dynamic-style.css');
        }
        return view('generalsettings::custom-css',compact('custom_css'));
    }

    public function custom_js(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->custom_js($request);
        }
        $custom_js = '/* Write Custom js Here */';
        if (file_exists('assets/frontend/js/dynamic-script.js')) {
            $custom_js = file_get_contents('assets/frontend/js/dynamic-script.js');
        }
        return view('generalsettings::custom-js',compact('custom_js'));
    }

    public function gdpr_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->gdpr_settings($request);
        }
        return view('generalsettings::gdpr-settings');
    }

    public function licence_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->licence_settings($request);
        }
        return view('generalsettings::licence-settings');
    }

    public function cache_settings(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->cache_settings($request);
        }
        return view('generalsettings::cache-settings');
    }

    public function database_upgrade(Request $request)
    {
        if($request->isMethod('post')){
            return (new GeneralSettingsService)->database_upgrade($request);
        }
        return view('generalsettings::database-upgrade');
    }


    //license settings
    public function license_settings()
    {
        return view('generalsettings::license-settings');
    }

    public function update_license_settings(Request $request)
    {
        $request->validate([
            'site_license_key' => 'required|string|max:191',
            'envato_username' => 'required|string|max:191',
        ]);

        $result = XgApiClient::activeLicense($request->site_license_key, $request->envato_username);
        $type = "danger";
        $msg = __("Could not able to verify your license key, please try after sometime, if you still face this issue, contact support");
        if (!empty($result["success"]) && $result["success"]) {
            update_static_option('site_license_key', $request->site_license_key);
            update_static_option('item_license_status', $result['success'] ? 'verified' : "");
            update_static_option('item_license_msg', $result['message']);
            $type = $result['success'] ? 'success' : "danger";
            $msg = $result['message'];
        }

        if($type == 'danger'){
            toastr_warning($msg);
        }
        if($type == 'success'){
            toastr_success($msg);
        }

        return redirect()->back();
    }


    public function software_update_check_settings(Request $request)
    {
        //run app update and database migrate here for test...
        return view('generalsettings::check-update');
    }

    public function update_version_check(Request $request)
    {

        $result = XgApiClient::checkForUpdate(get_static_option("site_license_key"), get_static_option("site_script_version"));

        if (isset($result["success"]) && $result["success"]) {


            $productUid = $result['data']['product_uid'] ?? null;
            $clientVersion = $result['data']['client_version'] ?? null;
            $latestVersion = $result['data']['latest_version'] ?? null;
            $productName = $result['data']['product'] ?? null;
            $releaseDate = $result['data']['release_date'] ?? null;
            $changelog = $result['data']['changelog'] ?? null;
            $phpVersionReq = $result['data']['php_version'] ?? null;
            $mysqlVersionReq = $result['data']['mysql_version'] ?? null;
            $extensions = $result['data']['extension'] ?? null;
            $isTenant = $result['data']['is_tenant'] ?? null;
            $daysDiff = $releaseDate;
            $msg = $result['data']['message'] ?? null;

            $output = "";
            $phpVCompare = version_compare(number_format((float)PHP_VERSION, 1), $phpVersionReq == 8 ? '8.0' : $phpVersionReq, '>=');
            $mysqlServerVersion = DB::select('select version()')[0]->{'version()'};
            $mysqlVCompare = version_compare(number_format((float)$mysqlServerVersion, 1), $mysqlVersionReq, '<=');
            $extensionReq = true;
            if ($extensions) {
                foreach (explode(',', str_replace(' ', '', strtolower($extensions))) as $extension) {
                    if (!empty($extension)) continue;
                    $extensionReq = XgApiClient::extensionCheck($extension);
                }
            }
            if (($phpVCompare === false || $mysqlVCompare === false) && $extensionReq === false) {
                $output .= '<div class="text-danger">' . __('Your server does not have required software version installed.  Required: Php') . $phpVersionReq == 8 ? '8.0' : $phpVersionReq . ', Mysql' . $mysqlVersionReq . '/ Extensions:' . $extensions . 'etc </div>';
                return response()->json(["msg" => $result["message"], "type" => "success", "markup" => $output]);
            }

            if (!empty($latestVersion)) {
                $output .= '<div class="text-success">' . $msg . '</div>';
                $output .= '<div class="card text-center" ><div class="card-header bg-transparent text-warning" >' . __("Please backup your database & script files before upgrading.") . '</div>';
                $output .= '<div class="card-body" ><h5 class="card-title" >' . __("new Version") . ' (' . $latestVersion . ') ' . __("is Available for") . ' ' . $productName . '!</h5 >';
                $updateActionUrl = route('admin.download.update.files', [$productUid, $isTenant]);
                $output .= '<a href = "#"  class="btn btn-warning" id="update_download_and_run_update" data-version="' . $latestVersion . '" data-action="' . $updateActionUrl . '"> <i class="fas fa-spinner fa-spin d-none"></i>' . __("Download & Update") . ' </a>';
                $output .= '<small class="text-warning d-block">' . __('it can take upto 5-10min to complete update download and initiate upgrade') . '</small></div>';
                $changesLongByLine = explode("\n", $changelog);
                $output .= '<p class="changes-log">';
                $output .= '<strong>' . __("Released:") . " " . $daysDiff . " " . "</strong><br>";
                $output .= "-------------------------------------------<br>";
                foreach ($changesLongByLine as $cg) {
                    $output .= $cg . "<br>";
                }
                $output .= '</p>';

                $output .= '</div>';
            }

            return response()->json(["msg" => $result["message"], "type" => "success", "markup" => $output]);
        }

        return response()->json(["msg" => $result["message"], "type" => "danger", "markup" => "<p class='text-danger'>" . $result["message"] . "</p>"]);

    }

    public function updateDownloadLatestVersion($productUid, $isTenant)
    {

        $version = \request()->get("version");
        //wrap this function through xgapiclient facades
        $getItemLicenseKey = get_static_option('site_license_key');
        $return_val = XgApiClient::downloadAndRunUpdateProcess($productUid, $isTenant, $getItemLicenseKey, $version);

        if (is_array($return_val)) {
            return response()->json(['msg' => $return_val['msg'], 'type' => $return_val['type']]);
        } elseif (is_bool($return_val) && $return_val) {
            return response()->json(['type' => 'success']);
        }
        //it is false
        return response()->json(['type' => 'danger']);
    }

    public function license_key_generate(Request $request)
    {
        $request->validate([
            "envato_purchase_code" => "required",
            "envato_username" => "required",
            "email" => "required",
        ]);
        $res = XgApiClient::VerifyLicense(purchaseCode: $request->envato_purchase_code, email: $request->email, envatoUsername: $request->envato_username);
        $type = $res["success"] ? "success" : "danger";
        $message = $res["message"];
        //store information in database
        if (!empty($res["success"])) {
            //success verify
            $res["data"] = is_array($res["data"]) ? $res["data"] : (array)$res["data"];
            update_static_option("license_product_uuid", $res["data"]["product_uid"] ?? "");
            update_static_option("site_license_key", $res["data"]["license_key"] ?? "");
        }
        update_static_option("license_purchase_code", $request->envato_purchase_code);
        update_static_option("license_email", $request->email);
        update_static_option("license_username", $request->envato_username);

        return back()->with(["msg" => $message, "type" => $type]);
    }

}
