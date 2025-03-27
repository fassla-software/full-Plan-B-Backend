<?php

//use App\Helpers\LanguageHelper;
use App\Models\{
    User,
    Order,
    Rating,
    JobPost,
    Language,
    UserSkill,
    MediaUpload,
    StaticOption,
    AdminNotification,
    ClientNotification,
    CommaConsume,
    FreelancerNotification
};
use App\Enums\MachineType;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use App\Helper\LanguageHelper;
use Modules\Pages\Entities\Page;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Messaging\CloudMessage;
use Modules\Subscription\Entities\UserSubscription;
use Carbon\Carbon;
use App\Models\OperationCost;

function minusUserAvailableLimit($UserSubscription, $operationType)
{
    if ($UserSubscription) {

        $operation = OperationCost::where('operation_type', $operationType)->first();

        $updatedLimit = $UserSubscription->limit - $operation?->cost ?? 0;
        $UserSubscription->update([
            'limit' => $updatedLimit,
        ]);

        CommaConsume::create([
            'user_id' => $UserSubscription->user_id,
            'user_subscription_id' => $UserSubscription->id,
            'operation_cost_id' => $operation?->id ?? 0,
            'consumed_limit' => $operation?->cost ?? 0,
            'remaining_limit' => $updatedLimit,
        ]);
    }
};

function getCurrentUserSubsicription(User $user)
{
    return $user->subscriptions()
        ->where('payment_status', 'complete')
        ->whereDate('expire_date', '>', Carbon::now())
        ->latest()
        ->first();
};

function getModelClassFromType(?string $type = null)
{
    $types = [
        MachineType::heavyEquipment->value => \App\Models\HeavyEquipmentJob::class,
        MachineType::vehicleRental->value => \App\Models\VehicleRentalJob::class,
        MachineType::craneRental->value => \App\Models\CraneRentalJob::class,
        MachineType::generatorRental->value => \App\Models\GeneratorRentalJop::class,
        MachineType::scaffoldingToolsRental->value => \App\Models\ScaffoldingAndMetalFormworkRentalJob::class,
        // Add other sub-category models here
    ];

    if (!isset($type)) return $types;

    return $types[$type] ?? null;
}

function getEquipmentModelFromType($type)
{
    $types = [
        MachineType::heavyEquipment->value => \App\Models\HeavyEquipment::class,
        MachineType::vehicleRental->value => \App\Models\VehicleRental::class,
        MachineType::craneRental->value => \App\Models\CraneRental::class,
        MachineType::generatorRental->value => \App\Models\GeneratorRental::class,
        MachineType::scaffoldingToolsRental->value => \App\Models\ScaffoldingAndMetalFormworkRental::class,
        // Add other sub-category models here
    ];

    return $types[$type] ?? null;
}

function paginateCollection($collection, $perPage, $currentPage)
{
    $total = $collection->count();
    $items = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

    return [
        'data' => $items,
        'current_page' => $currentPage,
        'per_page' => $perPage,
        'total' => $total,
        'last_page' => ceil($total / $perPage),
    ];
}

function checkSubsicriptionAvailability(int $neededCommas, ?int $user = null): bool
{
    if (!isset($user)) $user = auth()->user()->id;
    $total_limit = UserSubscription::where('user_id', $user->id)
        ->where('payment_status', 'complete')
        ->whereDate('expire_date', '>', Carbon::now())
        ->sum('limit');

    $createdAt = Carbon::parse($user->created_at);
    $isInTrialPeriod = $createdAt->diffInDays(Carbon::now()) < 30;

    if ($total_limit > $neededCommas || $isInTrialPeriod) {
        return true;
    }

    return false;
}

function render_twitter_meta_image_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';
    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="twitter:description" content="' . $image_details['img_url'] . '">';
    }
    return $output;
}

function canonical_url()
{
    if (\Illuminate\Support\Str::startsWith($current = url()->current(), 'https://www')) {
        return str_replace('https://www.', 'https://', $current);
    }

    return str_replace('https://', 'https://www.', $current);
}

function active_menu($url)
{
    return $url == request()->path() ? 'active' : '';
}
function active_menu_frontend($url)
{

    return $url == request()->path() ? 'current-menu-item' : '';
}
function check_image_extension($file)
{
    $extension = strtolower($file->getClientOriginalExtension());
    if ($extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension = 'gif') {
        return false;
    }
    return true;
}
function render_image_markup_by_attachment_id($id, $class = null, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);

    if (!empty($image_details)) {
        $class_list = !empty($class) ? 'class="' . $class . '"' : '';
        if (empty($image_details['img_url'])) {
            return '';
        }

        $output = '<img src="' . $image_details['img_url'] . '" ' . $class_list . ' alt="' . $image_details['img_alt'] . '"/>';
    }
    return $output;
}

function formatBytes($size, $precision = 2)
{
    $base = log($size, 1024);
    $suffixes = array('', 'KB', 'MB', 'GB', 'TB');

    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}


function set_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    }
    return false;
}
function get_static_option($key, $default = null)
{
    $option_name = $key;
    try {
        $value = \Illuminate\Support\Facades\Cache::remember($option_name, 600, function () use ($option_name) {
            return StaticOption::where('option_name', $option_name)->first();
        });
    } catch (\Exception $e) {
    }

    return $value->option_value ?? $default;
}

function get_default_language()
{
    $defaultLang = Language::where('default', 1)->first();
    return $defaultLang->slug;
}

function update_static_option($key, $value)
{
    if (!StaticOption::where('option_name', $key)->first()) {
        StaticOption::create([
            'option_name' => $key,
            'option_value' => $value
        ]);
        return true;
    } else {
        StaticOption::where('option_name', $key)->update([
            'option_name' => $key,
            'option_value' => $value
        ]);
        \Illuminate\Support\Facades\Cache::forget($key);
        return true;
    }
    return false;
}
function delete_static_option($key)
{
    \Illuminate\Support\Facades\Cache::forget($key);
    return (bool) StaticOption::where('option_name', $key)->delete();
}

function sanitize_html($value)
{
    return htmlspecialchars(strip_tags($value));
}

function sanitizeArray($input_array, $secondary = false)
{
    $return_arr = [];
    if (is_array($input_array) && count($input_array)) {
        $return_arr = [];
        foreach ($input_array as $value) {
            $clean_data = is_array($value) ? sanitizeArray($value) : sanitize_html($value);
            if (is_string($clean_data) && strlen($clean_data)) {
                $return_arr[] = $clean_data;
            }
        }
        return $return_arr;
    }
}



function single_post_share($url, $title, $img_url)
{
    $output = '';
    //get current page url
    $encoded_url = urlencode($url);
    //get current page title
    $post_title = str_replace(' ', '%20', $title);

    //all social share link generate
    $facebook_share_link = 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url; //have to change this url
    $twitter_share_link = 'https://twitter.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;';
    $pinterest_share_link = 'https://pinterest.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;';
    $youtube_share_link = 'https://youtube.com/intent/tweet?text=' . $post_title . '&amp;url=' . $encoded_url . '&amp;';
    $instagram_share_link = 'https://instagram.com/pin/create/button/?url=' . $encoded_url . '&amp;media=' . $img_url . '&amp;description=' . $post_title;

    $output .= '<li class="list-item"><a class="facebook-bg" href="' . $facebook_share_link . '"><i class="lab la-facebook-f"></i></a></li>';
    $output .= '<li class="list-item"><a class="twitter-bg" href="' . $twitter_share_link . '"><i class="lab la-twitter"></i></a></li>';
    $output .= '<li class="list-item"><a class="pintarest-bg" href="' . $pinterest_share_link . '"><i class="lab la-pinterest-p"></i></a></li>';
    $output .= '<li class="list-item"><a class="youtube-bg" href="' . $youtube_share_link . '"><i class="lab la-youtube"></i></a></li>';
    $output .= '<li class="list-item"><a class="instagram-bg" href="' . $instagram_share_link . '"><i class="lab la-instagram"></i></a></li>';

    return $output;
}


function load_google_fonts()
{
    //google fonts link;
    $fonts_url = 'https://fonts.googleapis.com/css2?family=';
    //body fonts
    $body_font_family = get_static_option('body_font_family') ?? 'Roboto';
    $heading_font_family = get_static_option('heading_font_family') ??  'Source Sans Pro';
    $section_font_family = get_static_option('section_font_family') ??  'sans-serif';

    $load_body_font_family = str_replace(' ', '+', $body_font_family);
    $body_font_variant = get_static_option('body_font_variant');
    $body_font_variant_selected_arr = !empty($body_font_variant) ? unserialize($body_font_variant, ['class' => false]) : ['400'];
    $body_font_variant_selected_arr = !is_null($body_font_variant_selected_arr) ? $body_font_variant_selected_arr : ['400'];
    $load_body_font_variant = is_array($body_font_variant_selected_arr) ? implode(';', $body_font_variant_selected_arr) : '400';

    $body_italic = '';
    preg_match('/1,/', $load_body_font_variant, $match);
    if (count($match) > 0) {
        $body_italic =  'ital,';
    } else {
        $load_body_font_variant = str_replace('0,', '', $load_body_font_variant);
    }

    $fonts_url .= $load_body_font_family . ':' . $body_italic . 'wght@' . $load_body_font_variant;
    $load_section_font_family = str_replace(' ', '+', $section_font_family);
    $section_font_variant = get_static_option('section_font_variant');
    $section_font_variant_selected_arr = !empty($section_font_variant) ? unserialize($section_font_variant, ['class' => false]) : ['400'];
    $section_font_variant_selected_arr = !is_null($section_font_variant_selected_arr) ? $section_font_variant_selected_arr : ['400'];
    $load_section_font_variant = is_array($section_font_variant_selected_arr) ? implode(';', $section_font_variant_selected_arr) : '400';


    if (!empty(get_static_option('section_font_family')) && $section_font_family != $body_font_family) {

        $heading_italic = '';
        preg_match('/1,/', $load_section_font_variant, $match);
        if (count($match) > 0) {
            $heading_italic =  'ital,';
        } else {
            $load_section_font_variant = str_replace('0,', '', $load_section_font_variant);
        }

        $fonts_url .= '&family=' . $load_section_font_family . ':' . $heading_italic . 'wght@' . $load_section_font_variant;
    }

    return sprintf('<link rel="preconnect" href="https://fonts.gstatic.com"> <link href="%1$s&display=swap" rel="stylesheet">', $fonts_url);
}


function render_background_video_markup_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = $image_details['img_url'];
    }
    return $output;
}


function render_background_image_markup_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';

    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = 'style="background-image: url(' . $image_details['img_url'] . ');"';
    }
    return $output;
}
function render_favicon_by_id($id)
{
    $site_favicon = get_attachment_image_by_id($id, "full", false);
    $output = '';
    if (!empty($site_favicon)) {
        $output .= '<link rel="icon" href="' . $site_favicon['img_url'] . '" type="image/png">';
    }
    return $output;
}
function get_attachment_image_by_id($id, $size = null, $default = false)
{
    $image_details = MediaUpload::find($id);
    $return_val = [];
    $image_url = '';


    try {
        $image_url = Storage::renderUrl('media-uploader/' . $image_details?->path, $size, $image_details->load_from);
    } catch (\Exception $e) {
        return ['img_url' => '', 'img_alt' => '', 'image_id' => '', 'path' => ''];
    }

    if (file_exists('assets/uploads/media-uploader/' . optional($image_details)->path)) {
        $image_url = asset('assets/uploads/media-uploader/' . optional($image_details)->path);
    }

    if (!empty($id) && !empty($image_details)) {
        switch ($size) {
            case "large":
                if (file_exists('assets/uploads/media-uploader/large-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/large-' . $image_details->path);
                }
                break;
            case "grid":
                if (file_exists('assets/uploads/media-uploader/grid-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/grid-' . $image_details->path);
                }
                break;

            case "semi-large":
                if (file_exists('assets/uploads/media-uploader/semi-large-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/semi-large-' . $image_details->path);
                }
                break;
            case "thumb":
                if (file_exists('assets/uploads/media-uploader/thumb-' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/thumb-' . $image_details->path);
                } else {
                    if (file_exists('assets/uploads/media-uploader/' . $image_details->path)) {
                        $image_url = asset('assets/uploads/media-uploader/' . $image_details->path);
                    }
                }
                break;
            default:
                if (file_exists('assets/uploads/media-uploader/' . $image_details->path)) {
                    $image_url = asset('assets/uploads/media-uploader/' . $image_details->path);
                }
                break;
        }
    }

    if (!empty($image_details)) {
        $return_val['image_id'] = $image_details->id;
        $return_val['path'] = $image_details->path;
        $return_val['img_url'] = $image_url;
        $return_val['img_alt'] = $image_details->alt;
    } elseif (empty($image_details) && $default) {
        $return_val['img_url'] = asset('assets/uploads/no-image.png');
    }

    return $return_val;
}

function get_attachment_url_only($id, $size = null, $default = false)
{
    $image_details = MediaUpload::find($id);

    if (!$image_details) {
        return $default ? asset('assets/uploads/no-image.png') : '';
    }

    $base_path = 'assets/uploads/media-uploader/';
    $path_suffix = $size ? $size . '-' : '';
    $image_path = $base_path . $path_suffix . $image_details->path;

    // Check if the specified size image exists, else fallback to the original
    if (file_exists($image_path)) {
        return asset($image_path);
    }

    // Fallback to original size if specific size is not available
    if (file_exists($base_path . $image_details->path)) {
        return asset($base_path . $image_details->path);
    }

    // Return default image if set to true
    return $default ? asset('assets/uploads/no-image.png') : '';
}

function get_user_lang()
{
    return $lang = LanguageHelper::user_lang_slug();
}

function get_user_lang_direction()
{
    $default = \App\Models\Language::where('default', 1)->first();
    $user_direction = \App\Models\Language::where('slug', session()->get('lang'))->first();

    // Fallback if no session language is set or user_direction is null
    if (!empty(session()->get('lang')) && $user_direction) {
        return $user_direction->direction;
    }

    // Fallback if default is null
    if ($default) {
        return $default->direction;
    }

    // Final fallback if no valid direction is found
    return 'ltr'; // Default to 'ltr' (left-to-right) if nothing else is available
}

function filter_static_option_value(string $index, array $array = [])
{
    return $array[$index] ?? '';
}

function render_og_meta_image_by_attachment_id($id, $size = 'full')
{
    if (empty($id)) return '';
    $output = '';
    $image_details = get_attachment_image_by_id($id, $size);
    if (!empty($image_details)) {
        $output = ' <meta property="og:image" content="' . $image_details['img_url'] . '">';
    }
    return $output;
}


function setEnvValue(array $values)
{

    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
        foreach ($values as $envKey => $envValue) {

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, "\n", $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

            // If key does not exist, add it
            if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                $str .= "{$envKey}={$envValue}\n";
            } else {
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
    }

    $str = substr($str, 0, -1);
    if (!file_put_contents($envFile, $str)) return false;
    return true;
}
function get_language_by_slug($slug)
{
    $lang_details = \App\Language::where('slug', $slug)->first();
    return !empty($lang_details) ? $lang_details->name : '';
}
function getAllCurrency(): array
{
    return [
        'USD' => '$',
        'EUR' => '€',
        'INR' => '₹',
        'IDR' => 'Rp',
        'AUD' => 'A$',
        'SGD' => 'S$',
        'JPY' => '¥',
        'GBP' => '£',
        'MYR' => 'RM',
        'PHP' => '₱',
        'THB' => '฿',
        'KRW' => '원',
        'NGN' => '₦',
        'GHS' => 'GH₵',
        'BRL' => 'R$',
        'BIF' => 'FBu',
        'CAD' => 'C$',
        'CDF' => 'FC',
        'CVE' => 'Esc',
        'GHP' => 'GH₵',
        'GMD' => 'D',
        'GNF' => 'FG',
        'KES' => 'Kes',
        'LRD' => 'L$',
        'MWK' => 'MK',
        'MZN' => 'MT',
        'RWF' => 'R₣',
        'SLL' => 'Le',
        'STD' => 'Db',
        'TZS' => 'TSh',
        'UGX' => 'USh',
        'XAF' => 'FCFA',
        'XOF' => 'CFA',
        'ZMK' => 'ZK',
        'ZMW' => 'ZK',
        'ZWD' => 'Z$',
        'AED' => 'د.إ',
        'AFN' => '؋',
        'ALL' => 'L',
        'AMD' => '֏',
        'ANG' => 'NAf',
        'AOA' => 'Kz',
        'ARS' => '$',
        'AWG' => 'ƒ',
        'AZN' => '₼',
        'BAM' => 'KM',
        'BBD' => 'Bds$',
        'BDT' => '৳',
        'BGN' => 'Лв',
        'BMD' => '$',
        'BND' => 'B$',
        'BOB' => 'Bs',
        'BSD' => 'B$',
        'BWP' => 'P',
        'BZD' => '$',
        'CHF' => 'CHf',
        'CNY' => '¥',
        'CLP' => '$',
        'COP' => '$',
        'CRC' => '₡',
        'CZK' => 'Kč',
        'DJF' => 'Fdj',
        'DKK' => 'Kr',
        'DOP' => 'RD$',
        'DZD' => 'دج',
        'EGP' => 'E£',
        'ETB' => 'ብር',
        'FJD' => 'FJ$',
        'FKP' => '£',
        'GEL' => 'ლ',
        'GIP' => '£',
        'GTQ' => 'Q',
        'GYD' => 'G$',
        'HKD' => 'HK$',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => 'Ft',
        'ILS' => '₪',
        'ISK' => 'kr',
        'JMD' => '$',
        'KGS' => 'Лв',
        'KHR' => '៛',
        'KMF' => 'CF',
        'KYD' => '$',
        'KZT' => '₸',
        'LAK' => '₭',
        'LBP' => 'ل.ل.',
        'LKR' => 'ரூ',
        'LSL' => 'L',
        'MAD' => 'MAD',
        'MDL' => 'L',
        'MGA' => 'Ar',
        'MKD' => 'Ден',
        'MMK' => 'K',
        'MNT' => '₮',
        'MOP' => 'MOP$',
        'MRO' => 'MRU',
        'MUR' => '₨',
        'MVR' => 'Rf',
        'MXN' => 'Mex$',
        'NAD' => 'N$',
        'NIO' => 'C$',
        'NOK' => 'kr',
        'NPR' => 'रू',
        'NZD' => '$',
        'PAB' => 'B/.',
        'PEN' => 'S/',
        'PGK' => 'K',
        'PKR' => '₨',
        'PLN' => 'zł',
        'PYG' => '₲',
        'QAR' => 'QR',
        'RON' => 'lei',
        'RSD' => 'din',
        'RUB' => '₽',
        'SAR' => 'SR',
        'SBD' => 'Si$',
        'SCR' => 'SR',
        'SEK' => 'kr',
        'SHP' => '£',
        'SOS' => 'Sh.so.',
        'SRD' => '$',
        'SZL' => 'E',
        'TJS' => 'ЅM',
        'TRY' => '₺',
        'TTD' => 'TT$',
        'TWD' => 'NT$',
        'UAH' => '₴',
        'UYU' => '$U',
        'UZS' => 'so\'m',
        'VND' => '₫',
        'VUV' => 'VT',
        'WST' => 'WS$',
        'XCD' => '$',
        'XPF' => '₣',
        'YER' => '﷼',
        'ZAR' => 'R',
        'JOD' => 'د.أ'
    ];
}

function site_currency_symbol($text = false)
{
    $all_currency = getAllCurrency();

    $symbol = '$';
    $global_currency = get_static_option('site_global_currency');

    foreach ($all_currency as $currency => $sym) {
        if ($global_currency == $currency) {
            $symbol = $text ? $currency : $sym;
            break;
        }
    }

    return $symbol;
}
function amount_with_currency_symbol($amount, $text = false)
{
    $decimal_yes_or_no = get_static_option('enable_disable_decimal_point');

    if ($decimal_yes_or_no == 'yes') {
        $amount = number_format((float) $amount, 2, '.', ',');
    } else {
        $amount = number_format((int) $amount);
    }
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = $symbol . $amount;
    if ($position == 'right') {
        $return_val = $amount . $symbol;
    }
    return $return_val;
}
function get_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_' . get_user_lang() . '_footer_copyright');
    $footer_copyright_text = str_replace(array('{copy}', '{year}'), array('&copy;', date('Y')), $footer_copyright_text);
    return $footer_copyright_text;
}
function get_country_field($name, $id, $class)
{
    return '<select style="height:50px;" name="' . $name . '" id="' . $id . '" class="' . $class . '"><option value="">' . __('Select Country') . '</option><option value="Afghanistan" >Afghanistan</option><option value="Albania" >Albania</option><option value="Algeria" >Algeria</option><option value="American Samoa" >American Samoa</option><option value="Andorra" >Andorra</option><option value="Angola" >Angola</option><option value="Anguilla" >Anguilla</option><option value="Antarctica" >Antarctica</option><option value="Antigua and Barbuda" >Antigua and Barbuda</option><option value="Argentina" >Argentina</option><option value="Armenia" >Armenia</option><option value="Aruba" >Aruba</option><option value="Australia" >Australia</option><option value="Austria" >Austria</option><option value="Azerbaijan" >Azerbaijan</option><option value="Bahamas" >Bahamas</option><option value="Bahrain" >Bahrain</option><option value="Bangladesh" >Bangladesh</option><option value="Barbados" >Barbados</option><option value="Belarus" >Belarus</option><option value="Belgium" >Belgium</option><option value="Belize" >Belize</option><option value="Benin" >Benin</option><option value="Bermuda" >Bermuda</option><option value="Bhutan" >Bhutan</option><option value="Bolivia" >Bolivia</option><option value="Bosnia and Herzegovina" >Bosnia and Herzegovina</option><option value="Botswana" >Botswana</option><option value="Bouvet Island" >Bouvet Island</option><option value="Brazil" >Brazil</option><option value="British Indian Ocean Territory" >British Indian Ocean Territory</option><option value="Brunei Darussalam" >Brunei Darussalam</option><option value="Bulgaria" >Bulgaria</option><option value="Burkina Faso" >Burkina Faso</option><option value="Burundi" >Burundi</option><option value="Cambodia" >Cambodia</option><option value="Cameroon" >Cameroon</option><option value="Canada" >Canada</option><option value="Cape Verde" >Cape Verde</option><option value="Cayman Islands" >Cayman Islands</option><option value="Central African Republic" >Central African Republic</option><option value="Chad" >Chad</option><option value="Chile" >Chile</option><option value="China" >China</option><option value="Christmas Island" >Christmas Island</option><option value="Cocos (Keeling) Islands" >Cocos (Keeling) Islands</option><option value="Colombia" >Colombia</option><option value="Comoros" >Comoros</option><option value="Cook Islands" >Cook Islands</option><option value="Costa Rica" >Costa Rica</option><option value="Croatia (Hrvatska)" >Croatia (Hrvatska)</option><option value="Cuba" >Cuba</option><option value="Cyprus" >Cyprus</option><option value="Czech Republic" >Czech Republic</option><option value="Democratic Republic of the Congo" >Democratic Republic of the Congo</option><option value="Denmark" >Denmark</option><option value="Djibouti" >Djibouti</option><option value="Dominica" >Dominica</option><option value="Dominican Republic" >Dominican Republic</option><option value="East Timor" >East Timor</option><option value="Ecuador" >Ecuador</option><option value="Egypt" >Egypt</option><option value="El Salvador" >El Salvador</option><option value="Equatorial Guinea" >Equatorial Guinea</option><option value="Eritrea" >Eritrea</option><option value="Estonia" >Estonia</option><option value="Ethiopia" >Ethiopia</option><option value="Falkland Islands (Malvinas)" >Falkland Islands (Malvinas)</option><option value="Faroe Islands" >Faroe Islands</option><option value="Fiji" >Fiji</option><option value="Finland" >Finland</option><option value="France" >France</option><option value="France, Metropolitan" >France, Metropolitan</option><option value="French Guiana" >French Guiana</option><option value="French Polynesia" >French Polynesia</option><option value="French Southern Territories" >French Southern Territories</option><option value="Gabon" >Gabon</option><option value="Gambia" >Gambia</option><option value="Georgia" >Georgia</option><option value="Germany" >Germany</option><option value="Ghana" >Ghana</option><option value="Gibraltar" >Gibraltar</option><option value="Greece" >Greece</option><option value="Greenland" >Greenland</option><option value="Grenada" >Grenada</option><option value="Guadeloupe" >Guadeloupe</option><option value="Guam" >Guam</option><option value="Guatemala" >Guatemala</option><option value="Guernsey" >Guernsey</option><option value="Guinea" >Guinea</option><option value="Guinea-Bissau" >Guinea-Bissau</option><option value="Guyana" >Guyana</option><option value="Haiti" >Haiti</option><option value="Heard and Mc Donald Islands" >Heard and Mc Donald Islands</option><option value="Honduras" >Honduras</option><option value="Hong Kong" >Hong Kong</option><option value="Hungary" >Hungary</option><option value="Iceland" >Iceland</option><option value="India" >India</option><option value="Indonesia" >Indonesia</option><option value="Iran (Islamic Republic of)" >Iran (Islamic Republic of)</option><option value="Iraq" >Iraq</option><option value="Ireland" >Ireland</option><option value="Isle of Man" >Isle of Man</option><option value="Israel" >Israel</option><option value="Italy" >Italy</option><option value="Ivory Coast" >Ivory Coast</option><option value="Jamaica" >Jamaica</option><option value="Japan" >Japan</option><option value="Jersey" >Jersey</option><option value="Jordan" >Jordan</option><option value="Kazakhstan" >Kazakhstan</option><option value="Kenya" >Kenya</option><option value="Kiribati" >Kiribati</option><option value="Korea, Democratic People\'s Republic of" >Korea, Democratic People\'s Republic of</option><option value="Korea, Republic of" >Korea, Republic of</option><option value="Kosovo" >Kosovo</option><option value="Kuwait" >Kuwait</option><option value="Kyrgyzstan" >Kyrgyzstan</option><option value="Lao People\'s Democratic Republic" >Lao People\'s Democratic Republic</option><option value="Latvia" >Latvia</option><option value="Lebanon" >Lebanon</option><option value="Lesotho" >Lesotho</option><option value="Liberia" >Liberia</option><option value="Libyan Arab Jamahiriya" >Libyan Arab Jamahiriya</option><option value="Liechtenstein" >Liechtenstein</option><option value="Lithuania" >Lithuania</option><option value="Luxembourg" >Luxembourg</option><option value="Macau" >Macau</option><option value="Madagascar" >Madagascar</option><option value="Malawi" >Malawi</option><option value="Malaysia" >Malaysia</option><option value="Maldives" >Maldives</option><option value="Mali" >Mali</option><option value="Malta" >Malta</option><option value="Marshall Islands" >Marshall Islands</option><option value="Martinique" >Martinique</option><option value="Mauritania" >Mauritania</option><option value="Mauritius" >Mauritius</option><option value="Mayotte" >Mayotte</option><option value="Mexico" >Mexico</option><option value="Micronesia, Federated States of" >Micronesia, Federated States of</option><option value="Moldova, Republic of" >Moldova, Republic of</option><option value="Monaco" >Monaco</option><option value="Mongolia" >Mongolia</option><option value="Montenegro" >Montenegro</option><option value="Montserrat" >Montserrat</option><option value="Morocco" >Morocco</option><option value="Mozambique" >Mozambique</option><option value="Myanmar" >Myanmar</option><option value="Namibia" >Namibia</option><option value="Nauru" >Nauru</option><option value="Nepal" >Nepal</option><option value="Netherlands" >Netherlands</option><option value="Netherlands Antilles" >Netherlands Antilles</option><option value="New Caledonia" >New Caledonia</option><option value="New Zealand" >New Zealand</option><option value="Nicaragua" >Nicaragua</option><option value="Niger" >Niger</option><option value="Nigeria" >Nigeria</option><option value="Niue" >Niue</option><option value="Norfolk Island" >Norfolk Island</option><option value="North Macedonia" >North Macedonia</option><option value="Northern Mariana Islands" >Northern Mariana Islands</option><option value="Norway" >Norway</option><option value="Oman" >Oman</option><option value="Pakistan" >Pakistan</option><option value="Palau" >Palau</option><option value="Palestine" >Palestine</option><option value="Panama" >Panama</option><option value="Papua New Guinea" >Papua New Guinea</option><option value="Paraguay" >Paraguay</option><option value="Peru" >Peru</option><option value="Philippines" >Philippines</option><option value="Pitcairn" >Pitcairn</option><option value="Poland" >Poland</option><option value="Portugal" >Portugal</option><option value="Puerto Rico" >Puerto Rico</option><option value="Qatar" >Qatar</option><option value="Republic of Congo" >Republic of Congo</option><option value="Reunion" >Reunion</option><option value="Romania" >Romania</option><option value="Russian Federation" >Russian Federation</option><option value="Rwanda" >Rwanda</option><option value="Saint Kitts and Nevis" >Saint Kitts and Nevis</option><option value="Saint Lucia" >Saint Lucia</option><option value="Saint Vincent and the Grenadines" >Saint Vincent and the Grenadines</option><option value="Samoa" >Samoa</option><option value="San Marino" >San Marino</option><option value="Sao Tome and Principe" >Sao Tome and Principe</option><option value="Saudi Arabia" >Saudi Arabia</option><option value="Senegal" >Senegal</option><option value="Serbia" >Serbia</option><option value="Seychelles" >Seychelles</option><option value="Sierra Leone" >Sierra Leone</option><option value="Singapore" >Singapore</option><option value="Slovakia" >Slovakia</option><option value="Slovenia" >Slovenia</option><option value="Solomon Islands" >Solomon Islands</option><option value="Somalia" >Somalia</option><option value="South Africa" >South Africa</option><option value="South Georgia South Sandwich Islands" >South Georgia South Sandwich Islands</option><option value="South Sudan" >South Sudan</option><option value="Spain" >Spain</option><option value="Sri Lanka" >Sri Lanka</option><option value="St. Helena" >St. Helena</option><option value="St. Pierre and Miquelon" >St. Pierre and Miquelon</option><option value="Sudan" >Sudan</option><option value="Suriname" >Suriname</option><option value="Svalbard and Jan Mayen Islands" >Svalbard and Jan Mayen Islands</option><option value="Swaziland" >Swaziland</option><option value="Sweden" >Sweden</option><option value="Switzerland" >Switzerland</option><option value="Syrian Arab Republic" >Syrian Arab Republic</option><option value="Taiwan" >Taiwan</option><option value="Tajikistan" >Tajikistan</option><option value="Tanzania, United Republic of" >Tanzania, United Republic of</option><option value="Thailand" >Thailand</option><option value="Togo" >Togo</option><option value="Tokelau" >Tokelau</option><option value="Tonga" >Tonga</option><option value="Trinidad and Tobago" >Trinidad and Tobago</option><option value="Tunisia" >Tunisia</option><option value="Turkey" >Turkey</option><option value="Turkmenistan" >Turkmenistan</option><option value="Turks and Caicos Islands" >Turks and Caicos Islands</option><option value="Tuvalu" >Tuvalu</option><option value="Uganda" >Uganda</option><option value="Ukraine" >Ukraine</option><option value="United Arab Emirates" >United Arab Emirates</option><option value="United Kingdom" >United Kingdom</option><option value="United States" >United States</option><option value="United States minor outlying islands" >United States minor outlying islands</option><option value="Uruguay" >Uruguay</option><option value="Uzbekistan" >Uzbekistan</option><option value="Vanuatu" >Vanuatu</option><option value="Vatican City State" >Vatican City State</option><option value="Venezuela" >Venezuela</option><option value="Vietnam" >Vietnam</option><option value="Virgin Islands (British)" >Virgin Islands (British)</option><option value="Virgin Islands (U.S.)" >Virgin Islands (U.S.)</option><option value="Wallis and Futuna Islands" >Wallis and Futuna Islands</option><option value="Western Sahara" >Western Sahara</option><option value="Yemen" >Yemen</option><option value="Zambia" >Zambia</option><option value="Zimbabwe" >Zimbabwe</option></select>';
}
function google_captcha_check($token)
{
    if (empty(get_static_option('site_google_captcha_enable'))) {
        return ['success' => true];
    }
    $captha_url = 'https://www.google.com/recaptcha/api/siteverify';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $captha_url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array('secret' => get_static_option('site_google_captcha_v3_secret_key'), 'response' => $token)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);

    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response, true);
    return $result;
}

function render_payment_gateway_for_form()
{
    $output = '<div class="payment-gateway-wrapper payment_getway_image">';

    $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';

    $all_gateway = ['paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'marcadopago', 'instamojo', 'cashfree', 'payfast', 'midtrans', 'squareup', 'cinetpay', 'paytabs', 'billplz', 'zitopay', 'sslcommerce_gateway'];

    $output .= '<ul>';
    $cash_on_delivery = (bool) get_static_option('cash_on_delivery_gateway');
    if ($cash_on_delivery) {
        $output .= '<li data-gateway="cash_on_delivery" ><div class="img-select">';
        $output .= render_image_markup_by_attachment_id(get_static_option('cash_on_delivery_preview_logo'));
        $output .= '</div></li>';
    }
    foreach ($all_gateway as $gateway) {
        if (!empty(get_static_option($gateway . '_gateway'))) :
            $class = (get_static_option('site_default_payment_gateway') == $gateway) ? 'class="selected active"' : '';

            $output .= '<li data-gateway="' . $gateway . '" ' . $class . '><div class="img-select">';
            $output .= render_image_markup_by_attachment_id(get_static_option($gateway . '_preview_logo'));
            $output .= '</div></li>';
        endif;
    }
    $output .= '</ul>';

    $output .= '</div>';
    return $output;
}

function render_payment_gateway_for_form2($except = [])
{
    $output = '<div class="payment-gateway-wrapper payment_getway_image">';

    $output .= '<input type="hidden" name="selected_payment_gateway" value="' . get_static_option('site_default_payment_gateway') . '">';
    $all_gateway = ['paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'marcadopago', 'instamojo', 'cashfree', 'payfast', 'midtrans', 'squareup', 'cinetpay', 'paytabs', 'billplz', 'zitopay'];
    $output .= '<ul>';
    foreach ($all_gateway as $gateway) {
        if (in_array($gateway, $except)) {
            continue;
        }
        if (!empty(get_static_option($gateway . '_gateway'))) :
            $class = (get_static_option('site_default_payment_gateway') == $gateway) ? 'class="selected active"' : '';

            $output .= '<li data-gateway="' . $gateway . '" ' . $class . '><div class="img-select">';
            $output .= render_image_markup_by_attachment_id(get_static_option($gateway . '_preview_logo'));
            $output .= '</div></li>';
        endif;
    }
    $output .= '</ul>';

    $output .= '</div>';
    return $output;
}

function render_drag_drop_form_builder_markup($content = '')
{
    $output = '';

    $form_fields = json_decode($content);
    $output .= '<ul id="sortable" class="available-form-field main-fields">';
    if (!empty($form_fields)) {
        $select_index = 0;
        foreach ($form_fields->field_type as $key => $ftype) {
            $args = [];
            $required_field = '';
            if (property_exists($form_fields, 'field_required')) {
                $filed_requirement = (array)$form_fields->field_required;
                $required_field = !empty($filed_requirement[$key]) ? 'on' : '';
            }
            if ($ftype == 'select') {
                $args['select_option'] = isset($form_fields->select_options[$select_index]) ? $form_fields->select_options[$select_index] : '';
                $select_index++;
            }
            if ($ftype == 'file') {
                $args['mimes_type'] = isset($form_fields->mimes_type->$key) ? $form_fields->mimes_type->$key : '';
            }
            $output .= render_drag_drop_form_builder_field_markup($key, $ftype, $form_fields->field_name[$key], $form_fields->field_placeholder[$key], $required_field, $args);
        }
    } else {
        $output .= render_drag_drop_form_builder_field_markup('1', 'text', 'your-name', 'Your Name', '');
    }

    $output .= '</ul>';
    return $output;
}

function render_drag_drop_form_builder_field_markup($key, $type, $name, $placeholder, $required, $args = [])
{
    $required_check = !empty($required) ? 'checked' : '';
    $placeholder = htmlspecialchars(strip_tags($placeholder));
    $name = htmlspecialchars(strip_tags($name));
    $type = htmlspecialchars(strip_tags($type));
    $output = '<li class="ui-state-default">
                     <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="remove-fields">x</span>
                    <a data-toggle="collapse" href="#fileds_collapse_' . $key . '" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        ' . ucfirst($type) . ': <span
                                class="placeholder-name">' . $placeholder . '</span>
                    </a>';
    $output .= '<div class="collapse" id="fileds_collapse_' . $key . '">
            <div class="card card-body margin-top-30">
                <input type="hidden" class="form-control" name="field_type[]"
                       value="' . $type . '">
                <div class="form-group">
                    <label>' . __('Name') . '</label>
                    <input type="text" class="form-control " name="field_name[]"
                           placeholder="' . __('enter field name') . '"
                           value="' . $name . '" >
                </div>
                <div class="form-group">
                    <label>' . __('Placeholder/Label') . '</label>
                    <input type="text" class="form-control field-placeholder"
                           name="field_placeholder[]" placeholder="' . __('enter field placeholder/label') . '"
                           value="' . $placeholder . '" >
                </div>
                <div class="form-group">
                    <label ><strong>' . __('Required') . '</strong></label>
                    <label class="switch">
                        <input type="checkbox" class="field-required" ' . $required_check . ' name="field_required[' . $key . ']">
                        <span class="slider-yes-no"></span>
                    </label>
                </div>';
    if ($type == 'select') {
        $output .= '<div class="form-group">
                        <label>' . __('Options') . '</label>
                            <textarea name="select_options[]" class="form-control max-height-120" cols="30" rows="10"
                                required>' . strip_tags($args['select_option']) . '</textarea>
                           <small>' . __('separate option by new line') . '</small>
                    </div>';
    }
    if ($type == 'file') {
        $output .= '<div class="form-group"><label>' . __('File Type') . '</label><select name="mimes_type[' . $key . ']" class="form-control mime-type">';
        $output .= '<option value="mimes:jpg,jpeg,png"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:jpg,jpeg,png') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:jpg,jpeg,png') . '</option>';

        $output .= '<option value="mimes:txt,pdf"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:txt,pdf') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:txt,pdf') . '</option>';

        $output .= '<option value="mimes:doc,docx"';
        if (isset($args['mimes_type']) && $args['mimes_type'] == 'mimes:mimes:doc,docx') {
            $output .= "selected";
        }
        $output .= '>' . __('mimes:mimes:doc,docx') . '</option>';

        $output .= '</select></div>';
    }
    $output .= '</div></div></li>';

    return $output;
}

function custom_number_format($amount)
{
    return number_format((float)$amount, 2, '.', '');
}

function redirect_404_page()
{
    return view('frontend.pages.404');
}
function getVisIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function get_visitor_country()
{
    $return_val = 'not detected';
    $ip = getVisIpAddr();
    $ipdat = @json_decode(file_get_contents(
        "http://www.geoplugin.net/json.gp?ip=" . $ip
    ));

    $ipdat = (array) $ipdat;
    $return_val = isset($ipdat['geoplugin_countryCode']) ? $ipdat['geoplugin_countryCode'] : $return_val;

    return $return_val;
}
function get_user_name_by_id($id)
{
    $user = \App\User::find($id);
    return $user;
}
function all_languages()
{
    $languages = [
        [
            "value" => "af",
            "lang" => "af",
            "title" => "Afrikaans"
        ],
        [
            "value" => "ar",
            "lang" => "ar",
            "title" => "العربية"
        ],
        [
            "value" => "ary",
            "lang" => "ar",
            "title" => "العربية المغربية"
        ],
        [
            "value" => "as",
            "lang" => "as",
            "title" => "অসমীয়া"
        ],
        [
            "value" => "az",
            "lang" => "az",
            "title" => "Azərbaycan dili"
        ],
        [
            "value" => "azb",
            "lang" => "az",
            "title" => "گؤنئی آذربایجان"
        ],
        [
            "value" => "bel",
            "lang" => "be",
            "title" => "Беларуская мова"
        ],
        [
            "value" => "bg_BG",
            "lang" => "bg",
            "title" => "Български"
        ],
        [
            "value" => "bn_BD",
            "lang" => "bn",
            "title" => "বাংলা"
        ],
        [
            "value" => "bo",
            "lang" => "bo",
            "title" => "བོད་ཡིག"
        ],
        [
            "value" => "bs_BA",
            "lang" => "bs",
            "title" => "Bosanski"
        ],
        [
            "value" => "ca",
            "lang" => "ca",
            "title" => "Català"
        ],
        [
            "value" => "ceb",
            "lang" => "ceb",
            "title" => "Cebuano"
        ],
        [
            "value" => "cs_CZ",
            "lang" => "cs",
            "title" => "Čeština"
        ],
        [
            "value" => "cy",
            "lang" => "cy",
            "title" => "Cymraeg"
        ],
        [
            "value" => "da_DK",
            "lang" => "da",
            "title" => "Dansk"
        ],
        [
            "value" => "de_CH",
            "lang" => "de",
            "title" => "Deutsch (Schweiz)"
        ],
        [
            "value" => "de_AT",
            "lang" => "de",
            "title" => "Deutsch (Österreich)"
        ],
        [
            "value" => "de_CH_informal",
            "lang" => "de",
            "title" => "Deutsch (Schweiz, Du)"
        ],
        [
            "value" => "de_DE",
            "lang" => "de",
            "title" => "Deutsch"
        ],
        [
            "value" => "de_DE_formal",
            "lang" => "de",
            "title" => "Deutsch (Sie)"
        ],
        [
            "value" => "dsb",
            "lang" => "dsb",
            "title" => "Dolnoserbšćina"
        ],
        [
            "value" => "dzo",
            "lang" => "dz",
            "title" => "རྫོང་ཁ"
        ],
        [
            "value" => "el",
            "lang" => "el",
            "title" => "Ελληνικά"
        ],
        [
            "value" => "en_US",
            "lang" => "en",
            "title" => "English (USA)"
        ],
        [
            "value" => "en_AU",
            "lang" => "en",
            "title" => "English (Australia)"
        ],
        [
            "value" => "en_GB",
            "lang" => "en",
            "title" => "English (UK)"
        ],
        [
            "value" => "en_CA",
            "lang" => "en",
            "title" => "English (Canada)"
        ],
        [
            "value" => "en_ZA",
            "lang" => "en",
            "title" => "English (South Africa)"
        ],
        [
            "value" => "en_NZ",
            "lang" => "en",
            "title" => "English (New Zealand)"
        ],
        [
            "value" => "eo",
            "lang" => "eo",
            "title" => "Esperanto"
        ],
        [
            "value" => "es_AR",
            "lang" => "es",
            "title" => "Español de Argentina"
        ],
        [
            "value" => "es_EC",
            "lang" => "es",
            "title" => "Español de Ecuador"
        ],
        [
            "value" => "es_MX",
            "lang" => "es",
            "title" => "Español de México"
        ],
        [
            "value" => "es_ES",
            "lang" => "es",
            "title" => "Español"
        ],
        [
            "value" => "es_VE",
            "lang" => "es",
            "title" => "Español de Venezuela"
        ],
        [
            "value" => "es_CO",
            "lang" => "es",
            "title" => "Español de Colombia"
        ],
        [
            "value" => "es_CR",
            "lang" => "es",
            "title" => "Español de Costa Rica"
        ],
        [
            "value" => "es_PE",
            "lang" => "es",
            "title" => "Español de Perú"
        ],
        [
            "value" => "es_PR",
            "lang" => "es",
            "title" => "Español de Puerto Rico"
        ],
        [
            "value" => "es_UY",
            "lang" => "es",
            "title" => "Español de Uruguay"
        ],
        [
            "value" => "es_GT",
            "lang" => "es",
            "title" => "Español de Guatemala"
        ],
        [
            "value" => "es_CL",
            "lang" => "es",
            "title" => "Español de Chile"
        ],
        [
            "value" => "et",
            "lang" => "et",
            "title" => "Eesti"
        ],
        [
            "value" => "eu",
            "lang" => "eu",
            "title" => "Euskara"
        ],
        [
            "value" => "fa_IR",
            "lang" => "fa",
            "title" => "فارسی"
        ],
        [
            "value" => "fa_AF",
            "lang" => "fa",
            "title" => "(فارسی (افغانستان"
        ],
        [
            "value" => "fi",
            "lang" => "fi",
            "title" => "Suomi"
        ],
        [
            "value" => "fr_FR",
            "lang" => "fr",
            "title" => "Français"
        ],
        [
            "value" => "fr_BE",
            "lang" => "fr",
            "title" => "Français de Belgique"
        ],
        [
            "value" => "fr_CA",
            "lang" => "fr",
            "title" => "Français du Canada"
        ],
        [
            "value" => "fur",
            "lang" => "fur",
            "title" => "Friulian"
        ],
        [
            "value" => "gd",
            "lang" => "gd",
            "title" => "Gàidhlig"
        ],
        [
            "value" => "gl_ES",
            "lang" => "gl",
            "title" => "Galego"
        ],
        [
            "value" => "gu",
            "lang" => "gu",
            "title" => "ગુજરાતી"
        ],
        [
            "value" => "haz",
            "lang" => "haz",
            "title" => "هزاره گی"
        ],
        [
            "value" => "he_IL",
            "lang" => "he",
            "title" => "עִבְרִית"
        ],
        [
            "value" => "hi_IN",
            "lang" => "hi",
            "title" => "हिन्दी"
        ],
        [
            "value" => "hr",
            "lang" => "hr",
            "title" => "Hrvatski"
        ],
        [
            "value" => "hsb",
            "lang" => "hsb",
            "title" => "Hornjoserbšćina"
        ],
        [
            "value" => "hu_HU",
            "lang" => "hu",
            "title" => "Magyar"
        ],
        [
            "value" => "hy",
            "lang" => "hy",
            "title" => "Հայերեն"
        ],
        [
            "value" => "id_ID",
            "lang" => "id",
            "title" => "Bahasa Indonesia"
        ],
        [
            "value" => "is_IS",
            "lang" => "is",
            "title" => "Íslenska"
        ],
        [
            "value" => "it_IT",
            "lang" => "it",
            "title" => "Italiano"
        ],
        [
            "value" => "ja",
            "lang" => "ja",
            "title" => "日本語"
        ],
        [
            "value" => "jv_ID",
            "lang" => "jv",
            "title" => "Basa Jawa"
        ],
        [
            "value" => "ka_GE",
            "lang" => "ka",
            "title" => "ქართული"
        ],
        [
            "value" => "kab",
            "lang" => "kab",
            "title" => "Taqbaylit"
        ],
        [
            "value" => "kk",
            "lang" => "kk",
            "title" => "Қазақ тілі"
        ],
        [
            "value" => "km",
            "lang" => "km",
            "title" => "ភាសាខ្មែរ"
        ],
        [
            "value" => "kn",
            "lang" => "kn",
            "title" => "ಕನ್ನಡ"
        ],
        [
            "value" => "ko_KR",
            "lang" => "ko",
            "title" => "한국어"
        ],
        [
            "value" => "ckb",
            "lang" => "ku",
            "title" => "كوردی‎"
        ],
        [
            "value" => "lo",
            "lang" => "lo",
            "title" => "ພາສາລາວ"
        ],
        [
            "value" => "lt_LT",
            "lang" => "lt",
            "title" => "Lietuvių kalba"
        ],
        [
            "value" => "lv",
            "lang" => "lv",
            "title" => "Latviešu valoda"
        ],
        [
            "value" => "mk_MK",
            "lang" => "mk",
            "title" => "Македонски јазик"
        ],
        [
            "value" => "ml_IN",
            "lang" => "ml",
            "title" => "മലയാളം"
        ],
        [
            "value" => "mn",
            "lang" => "mn",
            "title" => "Монгол"
        ],
        [
            "value" => "mr",
            "lang" => "mr",
            "title" => "मराठी"
        ],
        [
            "value" => "ms_MY",
            "lang" => "ms",
            "title" => "Bahasa Melayu"
        ],
        [
            "value" => "my_MM",
            "lang" => "my",
            "title" => "ဗမာစာ"
        ],
        [
            "value" => "nb_NO",
            "lang" => "nb",
            "title" => "Norsk bokmål"
        ],
        [
            "value" => "ne_NP",
            "lang" => "ne",
            "title" => "नेपाली"
        ],
        [
            "value" => "nl_NL",
            "lang" => "nl",
            "title" => "Nederlands"
        ],
        [
            "value" => "nl_BE",
            "lang" => "nl",
            "title" => "Nederlands (België)"
        ],
        [
            "value" => "nl_NL_formal",
            "lang" => "nl",
            "title" => "Nederlands (Formeel)"
        ],
        [
            "value" => "nn_NO",
            "lang" => "nn",
            "title" => "Norsk nynorsk"
        ],
        [
            "value" => "oci",
            "lang" => "oc",
            "title" => "Occitan"
        ],
        [
            "value" => "pa_IN",
            "lang" => "pa",
            "title" => "ਪੰਜਾਬੀ"
        ],
        [
            "value" => "pl_PL",
            "lang" => "pl",
            "title" => "Polski"
        ],
        [
            "value" => "ps",
            "lang" => "ps",
            "title" => "پښتو"
        ],
        [
            "value" => "pt_BR",
            "lang" => "pt",
            "title" => "Português do Brasil"
        ],
        [
            "value" => "pt_PT_ao90",
            "lang" => "pt",
            "title" => "Português (AO90)"
        ],
        [
            "value" => "pt_AO",
            "lang" => "pt",
            "title" => "Português de Angola"
        ],
        [
            "value" => "pt_PT",
            "lang" => "pt",
            "title" => "Português"
        ],
        [
            "value" => "rhg",
            "lang" => "rhg",
            "title" => "Ruáinga"
        ],
        [
            "value" => "ro_RO",
            "lang" => "ro",
            "title" => "Română"
        ],
        [
            "value" => "ru_RU",
            "lang" => "ru",
            "title" => "Русский"
        ],
        [
            "value" => "sah",
            "lang" => "sah",
            "title" => "Сахалыы"
        ],
        [
            "value" => "snd",
            "lang" => "sd",
            "title" => "سنڌي"
        ],
        [
            "value" => "si_LK",
            "lang" => "si",
            "title" => "සිංහල"
        ],
        [
            "value" => "sk_SK",
            "lang" => "sk",
            "title" => "Slovenčina"
        ],
        [
            "value" => "skr",
            "lang" => "skr",
            "title" => "سرائیکی"
        ],
        [
            "value" => "sl_SI",
            "lang" => "sl",
            "title" => "Slovenščina"
        ],
        [
            "value" => "sq",
            "lang" => "sq",
            "title" => "Shqip"
        ],
        [
            "value" => "sr_RS",
            "lang" => "sr",
            "title" => "Српски језик"
        ],
        [
            "value" => "sv_SE",
            "lang" => "sv",
            "title" => "Svenska"
        ],
        [
            "value" => "sw",
            "lang" => "sw",
            "title" => "Kiswahili"
        ],
        [
            "value" => "szl",
            "lang" => "szl",
            "title" => "Ślōnskŏ gŏdka"
        ],
        [
            "value" => "ta_IN",
            "lang" => "ta",
            "title" => "தமிழ்"
        ],
        [
            "value" => "ta_LK",
            "lang" => "ta",
            "title" => "தமிழ்"
        ],
        [
            "value" => "te",
            "lang" => "te",
            "title" => "తెలుగు"
        ],
        [
            "value" => "th",
            "lang" => "th",
            "title" => "ไทย"
        ],
        [
            "value" => "tl",
            "lang" => "tl",
            "title" => "Tagalog"
        ],
        [
            "value" => "tr_TR",
            "lang" => "tr",
            "title" => "Türkçe"
        ],
        [
            "value" => "tt_RU",
            "lang" => "tt",
            "title" => "Татар теле"
        ],
        [
            "value" => "tah",
            "lang" => "ty",
            "title" => "Reo Tahiti"
        ],
        [
            "value" => "ug_CN",
            "lang" => "ug",
            "title" => "ئۇيغۇرچە"
        ],
        [
            "value" => "uk",
            "lang" => "uk",
            "title" => "Українська"
        ],
        [
            "value" => "ur",
            "lang" => "ur",
            "title" => "اردو"
        ],
        [
            "value" => "uz_UZ",
            "lang" => "uz",
            "title" => "O‘zbekcha"
        ],
        [
            "value" => "vi",
            "lang" => "vi",
            "title" => "Tiếng Việt"
        ],
        [
            "value" => "zh_TW",
            "lang" => "zh",
            "title" => "繁體中文"
        ],
        [
            "value" => "zh_HK",
            "lang" => "zh",
            "title" => "香港中文版"
        ],
        [
            "value" => "zh_CN",
            "lang" => "zh",
            "title" => "简体中文"
        ]
    ];

    return $languages;
}

function render_embed_google_map($address, $zoom = 10)
{
    if (empty($address)) {
        return;
    }
    printf(
        '<div class="elementor-custom-embed"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe></div>',
        rawurlencode($address),
        $zoom,
        $address
    );
}

function render_menu_by_id($id)
{
    $default_lang = get_user_lang();
    $mega_menu_enable = '';

    if (empty($id)) {
        //load default home page if menu is empty
        return '<li><a href="' . url('/') . '">' . __('Home') . '</a></li>';
    }
    $output = '';
    $menu_details_from_db = Menu::find($id);


    $menu_content = json_decode($menu_details_from_db->content);
    if (empty($menu_content)) {
        //load default home page if menu is empty
        return '<li><a href="' . url('/') . '">' . __('Home') . '</a></li>';
    }
    foreach ($menu_content as $menu_item) {
        $li_class = '';
        //set li class if page is current page

        $mega_menu_ids = [];
        if (property_exists($menu_item, 'items_id')) {
            $mega_menu_ids = explode(',', $menu_item->items_id);
        }


        if ($menu_item->ptype == 'static') {
            //[lang]
            $menu_title =  get_static_option(str_replace('[lang]', get_user_lang(), $menu_item->pname));
            $menu_slug = url('/') . '/' . get_static_option($menu_item->pslug);
            $li_class .= (request()->path() == get_static_option($menu_item->pslug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'dynamic') {
            $menu_title = '';
            $menu_slug = '';
            $page_details = Page::with('lang_front')->find($menu_item->pid);
            if (!empty($page_details)) {
                $menu_title = $page_details->title;
                $menu_slug = route('frontend.dynamic.page', [$page_details->slug, $page_details->id]);
                $li_class .= (request()->is(route('frontend.dynamic.page', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
            }
        } elseif ($menu_item->ptype == 'custom') {
            $menu_title = __($menu_item->pname);
            $menu_slug = str_replace('@url', url('/'), $menu_item->purl);
            $li_class .= (request()->is($menu_slug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'blog' || $menu_item->ptype == 'practice-area' || $menu_item->ptype == 'case' ||  $menu_item->ptype == 'appointment') {

            if ($menu_item->ptype == 'blog') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Blog::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.blog.single', [$page_details->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.blog.single', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            }
            if ($menu_item->ptype == 'practice-area') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\PracticeArea::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.preactice.area.single', [$page_details->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.preactice.single', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            } elseif ($menu_item->ptype == 'case') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Cases::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = $page_details->lang_front->title;
                    $menu_slug = route('frontend.case.single', [$page_details->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.case.single', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            } elseif ($menu_item->ptype == 'appointment') {
                $menu_title = '';
                $menu_slug = '';
                $page_details = \App\Appointment::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = $page_details->title;
                    $menu_slug = route('frontend.appointment.single', [$page_details->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.appointment.single', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            }
        } elseif ($menu_item->ptype == 'case_mega_menu') {
            $menu_title = get_static_option('product_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('product_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Cases::with('lang_front')->find($mega_menu_ids)->groupBy('category_id');
        } elseif ($menu_item->ptype == 'blog_mega_menu') {
            $menu_title = get_static_option('blog_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('blog_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Blog::with('lang_front')->find($mega_menu_ids)->groupBy('category_id');
        } elseif ($menu_item->ptype == 'appointment_mega_menu') {
            $menu_title = get_static_option('appointment_page_' . $default_lang . '_name');
            $mega_menu_enable = 'mega_menu';
            $menu_raw_path = get_static_option('appointment_page_slug');
            $menu_slug = url('/') . '/' . $menu_raw_path;
            $li_class .= (request()->is($menu_raw_path)) ? ' current-menu-item ' : '';
            $mega_menu_items = \App\Appointment::with('lang_front')->find($mega_menu_ids)->groupBy('categories_id');
        }

        $li_class .= property_exists($menu_item, 'children') ? ' menu-item-has-children ' : '';
        $li_class .= property_exists($menu_item, 'items_id') ? ' menu-item-has-mega-menu ' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        $li_class_markup = !empty($li_class) ? 'class="' . $li_class . '"' : '';
        //set li class if it has submenu
        $icon_value = property_exists($menu_item, 'icon') ? '<i class="' . purify_html($menu_item->icon) . '"></i>' : '';
        $link_target = property_exists($menu_item, 'antarget') ? 'target="' . purify_html($menu_item->antarget) . '"' : '';

        if (!empty($menu_slug) && !empty($menu_title)) { //start condition

            $output .= $indent_tab . '<li ' . $li_class_markup . '>' . $indent_line;
            $output .= $indent_tab . '<a href="' . $menu_slug . '" ' . $link_target . '>' . $icon_value . purify_html($menu_title) . '</a>' . $indent_line;

            //check for megamenu
            if (!empty($mega_menu_enable)) {
                $output .= '<div class="xg_mega_menu_wrapper ' . $menu_item->ptype . '">';
                $output .= '<div class="xg-mega-menu-container"><div class="row">';
                foreach ($mega_menu_items as $cat => $posts) {
                    $output .= '<div class="col-lg-3 col-md-6"><div class="xg-mega-menu-single-column-wrap">';
                    $output .= '<h4 class="mega-menu-title">' . get_mega_menu_cat_name_by_id($menu_item->ptype, $cat) . '</h4>';
                    $output .= '<ul>';
                    foreach ($posts as $post) {
                        $mega_menu_item_slug = get_mege_menu_item_url($menu_item->ptype, $post->lang_front->slug, $post->id);
                        $output .= '<li><a href="' . $mega_menu_item_slug . '">' . purify_html($post->lang_front->title) . '</a></li>';
                    }
                    $output .= '</ul>';
                    $output .= '</div></div>';
                }
                $output .= '</div></div></div>';
                $mega_menu_enable = '';
            }
            //check it has submenu
            if (property_exists($menu_item, 'children')) {
                $output .= render_submenu_children($menu_item->children);
            }
            //load li end tag
            $output .= $indent_tab . '</li>' . $indent_line;
        } // end condition
    }

    return $output;
}

/* render submenu */


function render_submenu_children($menu_children)
{
    $indent_line = "\n";
    $indent_tab = "\t";

    $output = $indent_tab . '<ul class="sub-menu">' . $indent_line;
    foreach ($menu_children as $menu_item) {

        $li_class = '';
        //set li class if page is current page

        if ($menu_item->ptype == 'static') {
            $menu_title = get_static_option(str_replace('[lang]', get_user_lang(), $menu_item->pname));
            $menu_slug = url('/') . '/' . get_static_option($menu_item->pslug);
            $li_class .= (request()->path() == get_static_option($menu_item->pslug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'dynamic') {
            $page_details = Page::with('lang_front')->find($menu_item->pid);
            $menu_title = !empty($page_details) ? $page_details->lang_front->title : '';
            $menu_slug = !empty($page_details) ? route('frontend.dynamic.page', [$page_details->lang_front->slug, $page_details->id]) : '';
            if (!empty($page_details)) {
                $li_class .= (request()->is(route('frontend.dynamic.page', [$page_details->lang_front->slug, $page_details->id])))   ? ' current-menu-item ' : '';
            }
        } elseif ($menu_item->ptype == 'custom') {
            $menu_title = __($menu_item->pname);
            $menu_slug = str_replace('@url', url('/'), $menu_item->purl);
            $li_class .= (request()->is($menu_slug)) ? ' current-menu-item ' : '';
        } elseif ($menu_item->ptype == 'practice_area' || $menu_item->ptype == 'blog' || $menu_item->ptype == 'appointment' ||  $menu_item->ptype == 'case') {
            $menu_title = '';
            $menu_slug = '';
            if ($menu_item->ptype == 'practice_area') {

                $page_details = \App\PracticeArea::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = $page_details->lang_front->title;
                    $menu_slug = route('frontend.practice.area.single', [$page_details->lang_front->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.practice.area.single', [$page_details->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            } elseif ($menu_item->ptype == 'blog') {

                $page_details = \App\Blog::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = $page_details->lang_front->title;
                    $menu_slug = route('frontend.blog.single', [$page_details->lang_front->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.blog.single', [$page_details->lang_front->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            } elseif ($menu_item->ptype == 'case') {

                $page_details = \App\Cases::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = !empty($page_details) ? $page_details->lang_front->title : '';
                    $menu_slug = route('frontend.case.single', [$page_details->lang_front->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.case.single', [$page_details->lang_front->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            } elseif ($menu_item->ptype == 'appointment') {

                $page_details = \App\Appointment::with('lang_front')->find($menu_item->pid);
                if (!empty($page_details)) {
                    $menu_title = !empty($page_details) ? $page_details->lang_front->title : '';
                    $menu_slug = route('frontend.appointment.single', [$page_details->lang_front->slug, $page_details->id]);
                    $li_class .= (request()->is(route('frontend.appointment.single', [$page_details->lang_front->slug, $page_details->id]))) ? ' current-menu-item ' : '';
                }
            }
        }


        $li_class .= property_exists($menu_item, 'children') ? ' menu-item-has-children ' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        $li_class_markup = !empty($li_class) ? 'class="' . $li_class . '"' : '';
        //set li class if it has submenu
        $icon_value = property_exists($menu_item, 'icon') ? '<i class="' . purify_html($menu_item->icon) . '"></i>' : '';
        $link_target = property_exists($menu_item, 'antarget') ? 'target="' . purify_html($menu_item->antarget) . '"' : '';

        if (!empty($menu_slug) && !empty($menu_title)) {
            $output .= $indent_tab . '<li ' . $li_class_markup . '>' . $indent_line;
            $output .= $indent_tab . '<a href="' . $menu_slug . '" ' . $link_target . '>' . $icon_value . purify_html($menu_title) . '</a>' . $indent_line;
        }
        //check it has submenu
        if (property_exists($menu_item, 'children')) {
            $output .= render_submenu_children($menu_item->children);
        }
        //load li end tag
        $output .= $indent_tab . '</li>' . $indent_line;
    }
    $output .= $indent_tab . '</ul>' . $indent_line;
    return $output;
}

/* render menu for drag & drop menu in admin panel */
function render_draggable_menu_by_id($id)
{
    $default_lang = get_default_language();
    $mega_menu_enable = '';
    $mega_menu_items = '';
    $output = '';
    $menu_details_from_db = App\Models\Menu::find($id);

    $menu_data = json_decode($menu_details_from_db->content);

    $page_id = 0;
    foreach ($menu_data as $menu) :
        $page_id++;

        $menu_title = '';
        $menu_attr = 'data-ptype="' . $menu->ptype . '" ';

        if ($menu->ptype == 'static') {
            $menu_attr .= ' data-pname="' . $menu->pname . '"';
            $menu_attr .= ' data-pslug="' . $menu->pslug . '"';
            $menu_title = get_static_option(str_replace('[lang]', get_default_language(), $menu->pname));
        } elseif ($menu->ptype == 'dynamic') {
            $menu_attr .= ' data-pid="' . $menu->pid . '"';
            $menu_details = Page::find($menu->pid);
            $menu_title = !empty($menu_details) ? $menu_details->title : '';
        } elseif ($menu->ptype == 'custom') {
            $menu_attr .= ' data-purl="' . $menu->purl . '"';
            $menu_attr .= ' data-pname="' . $menu->pname . '"';
            $menu_title = $menu->pname;
        } elseif ($menu->ptype == 'event' || $menu->ptype == 'blog' || $menu->ptype == 'case' || $menu->ptype == 'contribution') {
            $menu_attr .= ' data-pid="' . $menu->pid . '"';
            if ($menu->ptype == 'event') {
                $menu_details = \App\Event::find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
            } elseif ($menu->ptype == 'blog') {
                $menu_details = \App\Blog::with('lang_front')->find($menu->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
            } elseif ($menu->ptype == 'contribution') {
                $menu_details = \App\Contribution::find($menu->pid);
                $menu_title = !empty($menu_details) ?  $menu_details->lang_front->title : '';
            }
        } elseif ($menu->ptype == 'blog_mega_menu') {
            $menu_title = get_static_option('blog_page_' . $default_lang . '_name') . __('Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Blog::with('lang_front')->where(['status' => 'publish'])->get();
        } elseif ($menu->ptype == 'appointment_mega_menu') {
            $menu_title = get_static_option('appointment_page_' . $default_lang . '_name') . __('Mega Menu');
            $mega_menu_enable = 'mega_menu';
            $mega_menu_items = \App\Appointment::with('lang_front')->where(['status' => 'publish'])->get();
        }

        $mega_menu_ids = [];
        if (property_exists($menu, 'items_id')) {
            $mega_menu_ids = explode(',', $menu->items_id);
            $menu_attr .= ' data-items_id="' . $menu->items_id . '" ';
        }

        $icon_value = property_exists($menu, 'icon') ? 'value="' . purify_html($menu->icon) . '"' : '';
        $link_target = property_exists($menu, 'antarget') ? 'value="' . purify_html($menu->antarget) . '"' : '';
        $icon_data = property_exists($menu, 'icon') ? 'data-icon="' . purify_html($menu->icon) . '"' : '';

        $indent_line = "\n";
        $indent_tab = "\t";

        if (!empty($menu_title)) {
            $output .= '<li class="dd-item" data-id="' . $page_id . '" ' . $menu_attr . ' ' . $icon_data . '>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-handle">' . purify_html($menu_title) . '</div>' . $indent_line;
            $output .= $indent_tab . '<span class="remove_item">x</span>' . $indent_line;
            $output .= $indent_tab . '<span class="expand"><i class="ti-angle-down"></i></span>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-body hide">';
        }

        //add mega menu extra field here
        if (!empty($mega_menu_enable)) {
            $output .= '<label for="items_id">' . __('Select Items') . '</label>';
            $output .= '<select name="items_id" multiple class="form-control">';
            foreach ($mega_menu_items as $data) :
                $selected = in_array($data->id, $mega_menu_ids) ? 'selected' : '';
                $output .= '<option value="' . $data->id . '" ' . $selected . ' >' . purify_html($data->lang_front->title) . '</option>';
            endforeach;
            $output .= '</select>';
            $mega_menu_enable = '';
        } else {
            if (!empty($menu_title)) {
                $output .= '<input type="text" class="anchor_target" placeholder="eg: _target" ' . purify_html($link_target) . '/>';
                $output .= '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook" ' . purify_html($icon_value) . '/>';
            }
        }
        if (!empty($menu_title)) {
            $output .= '</div>' . $indent_line;
        }

        //check it has children or not
        if (property_exists($menu, 'children')) {
            $output .= render_draggable_menu_children($menu->children, $page_id);
        }
        $output .= '</li>' . $indent_line;

    endforeach;
    return $output;
}

/* render submenu of menu for drag & drop menu in admin panel */
function render_draggable_menu_children($children, $page_id)
{
    $indent_line = "\n";
    $indent_tab = "\t";

    $output = $indent_tab . '<ol class="dd-list">' . $indent_line;
    foreach ($children as $item) {
        $page_id++;
        $menu_title = '';
        $menu_attr = 'data-ptype="' . $item->ptype . '" ';

        if ($item->ptype == 'static') {

            $menu_attr .= ' data-pname="' . $item->pname . '"';
            $menu_attr .= ' data-pslug="' . $item->pslug . '"';
            $menu_title = get_static_option(str_replace('[lang]', get_default_language(), $item->pname));;
        } elseif ($item->ptype == 'dynamic') {

            $menu_attr .= ' data-pid="' . $item->pid . '"';
            $menu_details = Page::with('lang_front')->find($item->pid);
            $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
        } elseif ($item->ptype == 'custom') {
            $menu_attr .= ' data-purl="' . $item->purl . '"';
            $menu_attr .= ' data-pname="' . $item->pname . '"';
            $menu_title = $item->pname;
        } elseif ($item->ptype == 'service' || $item->ptype == 'blog' || $item->ptype == 'product' || $item->ptype == 'appointment') {
            $menu_attr .= ' data-pid="' . $item->pid . '"';
            if ($item->ptype == 'blog') {
                $menu_details = \App\Blog::with('lang_front')->find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
            } elseif ($item->ptype == 'appointment') {
                $menu_details = \App\Appointment::with('lang_front')->find($item->pid);
                $menu_title = !empty($menu_details) ? $menu_details->lang_front->title : '';
            }
        }
        $icon_value = property_exists($item, 'icon') ? 'value="' . purify_html($item->icon) . '"' : '';
        $icon_data = property_exists($item, 'icon') ? 'data-icon="' . purify_html($item->icon) . '"' : '';
        $link_target = property_exists($item, 'antarget') ? 'value="' . purify_html($item->antarget) . '"' : '';

        if (!empty($menu_title)) {
            $output .= $indent_tab . $indent_tab . '<li class="dd-item" data-id="' . $page_id . '" ' . $menu_attr . ' ' . $icon_data . '>' . $indent_line;
            $output .= $indent_tab . $indent_tab . $indent_tab . '<div class="dd-handle">' . purify_html($menu_title) . '</div>' . $indent_line;
            $output .= $indent_tab . $indent_tab . $indent_tab . '<span class="remove_item">x</span>' . $indent_line;
            $output .= $indent_tab . '<span class="expand"><i class="ti-angle-down"></i></span>' . $indent_line;
            $output .= $indent_tab . '<div class="dd-body hide">';

            $output .= '<input type="text" class="anchor_target" placeholder="eg: _blank" ' . purify_html($link_target) . '/>';
            $output .= '<input type="text" class="icon_picker" placeholder="eg: fas-fa-facebook" ' . purify_html($icon_value) . '/>';
            $output .= '</div>' . $indent_line;
        }

        if (property_exists($item, 'children')) {
            $output .= render_draggable_menu_children($item->children, $page_id);
        }
        if (!empty($menu_title)) {
            $output .= $indent_tab . $indent_tab . '</li>' . $indent_line;
        }
    }
    $output .= $indent_tab . '</ol>' . $indent_line;
    return $output;
}

function render_mega_menu_item_select_markup($type = '', $menu_id = null)
{
    $output = '';
    if (!empty($type)) {
        if ($type == 'practice_area_mega_menu') {
            $mega_menu_items = \App\PracticeArea::with('lang_front')->where(['status' => 'publish'])->get();
        } elseif ($type == 'case_mega_menu') {
            $mega_menu_items = \App\Cases::with('lang_front')->where(['status' => 'publish'])->get();
        } elseif ($type == 'blog_mega_menu') {
            $mega_menu_items = \App\Blog::with('lang_front')->where(['status' => 'publish'])->get();
        } elseif ($type == 'appointment_mega_menu') {
            $mega_menu_items = \App\Appointment::with('lang_front')->where(['status' => 'publish'])->get();
        }
        $output .= '<label for="items_id">' . __('Select Items') . '</label>';
        $output .= '<select name="items_id" multiple class="form-control">';
        foreach ($mega_menu_items as $data):
            $output .= '<option value="' . $data->id . '" >' . purify_html($data->lang_front->title) . '</option>';
        endforeach;
        $output .= '</select>';
    }
    return $output;
}
function get_mega_menu_cat_name_by_id($type, $cat_id)
{
    $return_val = '';

    switch ($type) {
        case ('service_mega_menu'):
            $cat_details = \App\ServiceCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        case ('product_mega_menu'):
            $cat_details = \App\ProductCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        case ('appointment_mega_menu'):
            $cat_details = \App\AppointmentCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        case ('blog_mega_menu'):
            $cat_details = \App\BlogCategory::with('lang_front')->find($cat_id);
            $return_val = !empty($cat_details) ? purify_html($cat_details->lang_front->name) : '';
            break;
        default:
            break;
    }

    return $return_val;
}
function get_mege_menu_item_url($type, $slug, $id)
{
    $return_val = '';
    switch ($type) {
        case ('service_mega_menu'):
            $return_val = route('frontend.services.single', [purify_html($slug), $id]);
            break;
        case ('product_mega_menu'):
            $return_val =  route('frontend.products.single', [purify_html($slug), $id]);
            break;
        case ('appointment_mega_menu'):
            $return_val =  route('frontend.appointment.single', [purify_html($slug), $id]);
            break;
        case ('blog_mega_menu'):
            $return_val =  route('frontend.blog.single', [purify_html($slug), $id]);
            break;
        default:
            break;
    }

    return $return_val;
}

function render_footer_copyright_text()
{
    $footer_copyright_text = get_static_option('site_footer_copyright');
    $footer_copyright_text = str_replace('{copy}', '&copy;', $footer_copyright_text);
    $footer_copyright_text = str_replace('{year}', date('Y'), $footer_copyright_text);

    return purify_html_raw($footer_copyright_text);
}
function render_admin_panel_widgets_list()
{
    return \plugins\WidgetBuilder\WidgetBuilderSetup::get_admin_panel_widgets();
}

function render_admin_saved_widgets($location)
{
    $output = '';
    $all_widgets = \App\Models\Widget::where(['widget_location' => $location])->orderBy('widget_order', 'asc')->get();
    foreach ($all_widgets as $widget) {
        $output .= \plugins\WidgetBuilder\WidgetBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $widget->widget_name,
            'id' => $widget->id,
            'type' => 'update',
            'order' => $widget->widget_order,
            'location' => $widget->widget_location
        ]);
    }

    return $output;
}

function get_admin_sidebar_list()
{
    return \plugins\WidgetBuilder\WidgetBuilderSetup::get_admin_widget_sidebar_list();
}

function render_frontend_sidebar($location, $args = [])
{
    $output = '';
    $all_widgets = \App\Models\Widget::where(['widget_location' => $location])->orderBy('widget_order', 'ASC')->get();
    foreach ($all_widgets as $widget) {
        $output .= \plugins\WidgetBuilder\WidgetBuilderSetup::render_widgets_by_name_for_frontend([
            'name' => $widget->widget_name,
            'location' => $location,
            'id' => $widget->id,
            'column' => $args['column'] ?? false,
            'column_class' => $args['column_class'] ?? null
        ]);
    }
    return $output;
}
function get_all_language()
{
    $all_lang = Language::orderBy('default', 'DESC')->get();
    return $all_lang;
}
function get_language_name_by_slug($slug)
{
    $data = Language::where('slug', $slug)->first();
    return $data->name;
}
function get_blog_category_by_id($id, $lang = null, $type = '')
{
    $default_lang = $lang ?? LanguageHelper::default_slug();
    $return_val = __('uncategorized');
    $blog_cat = \App\BlogCategory::find($id);

    if (!empty($blog_cat)) {
        $return_val = $blog_cat->getTranslation('name', $default_lang);
        if ($type == 'link') {
            $return_val = '<a href="' . route('frontend.blog.category', ['id' => $blog_cat->id, 'any' => Str::slug($blog_cat->name)]) . '">' . $blog_cat->name . '</a>';
        }
    }
    return $return_val;
}

function get_events_category_by_id($id, $lang = null, $type = '')
{
    $default_lang = $lang ?? LanguageHelper::default_slug();
    $return_val = __('uncategorized');
    $event_cat = \App\EventCategory::find($id);

    if (!empty($event_cat)) {
        $return_val = $event_cat->getTranslation('name', $default_lang);
        if ($type == 'link') {
            $return_val = '<a href="' . route('frontend.event.category', ['id' => $event_cat->id, 'any' => Str::slug($event_cat->name)]) . '">' . $event_cat->name . '</a>';
        }
    }
    return $return_val;
}

function custom_amount_with_currency_symbol($amount, $text = false)
{
    $amount = number_format((float) $amount, 0, '.', ',');
    $position = get_static_option('site_currency_symbol_position');
    $symbol = site_currency_symbol($text);
    $return_val = '<span class="sign">' . $symbol . '</span>' . $amount;
    if ($position == 'right') {
        $return_val = $amount . '<span class="sign">' . $symbol . '</span>';
    }
    return $return_val;
}

function float_amount_with_currency_symbol($amount, $text = false)
{
    $symbol = site_currency_symbol($text);
    $position = get_static_option('site_currency_symbol_position');
    $thousand_separator = get_static_option('site_currency_thousand_separator') ?? ',';
    $decimal_separator = get_static_option('site_currency_decimal_separator') ?? '.';

    if (empty($amount)) {
        $return_val = $symbol . $amount;
        if ($position == 'right') {
            $return_val = $amount . $symbol;
        }
    }

    //decimal enable disable
    $decimal_yes_or_no = get_static_option('enable_disable_decimal_point');
    $amount = $decimal_yes_or_no != 'disable' ? $amount = number_format((float)$amount, 2, $decimal_separator, $thousand_separator) : $amount = number_format((int) $amount);
    $return_val = $symbol . $amount;

    if ($position == 'right') {
        $return_val = $amount . ' ' . $symbol;
    }
    return $return_val;
}

function admin_default_lang()
{
    $default_lang = Language::where(['default' => 1, 'status' => 'publish'])->first();
    return $default_lang->slug;
}

function front_default_lang()
{
    $default_lang = !empty(session()->get('lang')) ? session()->get('lang') : Language::where('default', 1)->first()->slug;
    return $default_lang;
}

function get_default_language_direction()
{
    $default_lang = Language::where('default', 1)->first();
    return !empty($default_lang) ? $default_lang->direction : 'ltr';
}

function multilang_field_display($fields, $field_name, $lang)
{
    foreach ($fields as $field) {
        if ($field->lang == $lang) {
            return $field->$field_name;
        }
    }
}

function get_product_category_by_id($id, $type = '')
{
    $return_val = __('uncategorized');
    $prod_cat = \App\ProductCategory::with('lang_front')->find($id);
    if (!empty($prod_cat)) {
        $return_val = $prod_cat->lang_front->name;
        if ($type == 'link') {
            $return_val = '<a href="' . route('frontend.products.category', ['id' => $prod_cat->id, 'any' => Str::slug($prod_cat->lang_front->name)]) . '">' . $prod_cat->lang_front->name . '</a>';
        }
    }

    return $return_val;
}

function get_shipping_name_by_id($id)
{
    $shipping_details = \App\ProductShipping::find($id);
    return !empty($shipping_details) ? $shipping_details->title : "Undefined";
}
function get_image_category_name_by_id($id)
{
    $return_val = __('uncategorized');

    $category_details = \App\GalleryCategory::find($id);
    if (!empty($category_details)) {
        $return_val = $category_details->title;
    }

    return $return_val;
}

function is_tax_enable()
{
    return get_static_option('product_tax') && get_static_option('product_tax_system') == 'exclusive'  ? true : false;
}

function render_ratings($ratings)
{
    $return_val = '';
    switch ($ratings) {
        case (1):
            $return_val = '<i class="fas fa-star"></i>';
            break;
        case (2):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case (3):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case (4):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        case (5):
            $return_val = '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
            break;
        default:
            break;
    }
    return $return_val;
}

function get_cart_items()
{
    $old_cart_item = session()->get('cart_item');
    $return_val = !empty($old_cart_item) ? $old_cart_item : [];

    return $return_val;
}

function get_attachment_url_by_id($id, $size = null)
{
    $return_val =  get_attachment_image_by_id($id, $size);
    return $return_val['image_id'] ?? '';
}

function all_lang_slugs()
{
    return Language::all()->pluck('slug')->toArray();
}
function exist_slugs($model_data)
{
    return $model_data->lang_all->pluck('lang')->toArray();
}

function purify_html($html)
{
    return strip_tags(\Mews\Purifier\Facades\Purifier::clean($html));
}

function purify_html_raw($html)
{
    return \Mews\Purifier\Facades\Purifier::clean($html);
}

//New Menu Functions
function render_pages_list($lang = null)
{
    $instance = new \plugins\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_static_pages_list($lang);
}
function render_dynamic_pages_list($lang = null)
{
    $instance = new \plugins\MenuBuilder\MenuBuilderHelpers();
    return $instance->get_post_type_page_list($lang);
}
function render_mega_menu_list($lang = null)
{
    $instance = new \plugins\MenuBuilder\MegaMenuBuilderSetup();
    return $instance->render_mega_menu_list($lang);
}

function render_draggable_menu($id)
{
    $instance = new \plugins\MenuBuilder\MenuBuilderAdminRender();
    return $instance->render_admin_panel_menu($id);
}
function render_frontend_menu($id)
{
    $instance = new \plugins\MenuBuilder\MenuBuilderFrontendRender();
    return $instance->render_frrontend_panel_menu($id);
}

function get_percentage($amount, $numb)
{
    $numb = !empty($numb) ? $numb : 0;

    if ($amount > 0) {
        return round($numb / ($amount / 100), 2);
    }
    return 0;
}


function render_gallery_image_attachment_preview($gal_image)
{
    if (empty($gal_image)) {
        return;
    }
    $output = '';
    $gallery_images = explode('|', $gal_image);
    foreach ($gallery_images as $gl_img) {
        $work_section_img = get_attachment_image_by_id($gl_img, null, true);
        if (!empty($work_section_img)) {
            $output .= sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt=""> </div></div></div>', $work_section_img['img_url']);
        }
    }
    return $output;
}

function render_attachment_preview_for_admin($id)
{
    $markup = '';
    $header_bg_img = get_attachment_image_by_id($id, null, true);
    $img_url = $header_bg_img['img_url'] ?? '';
    $img_alt = $header_bg_img['img_alt'] ?? '';
    if (!empty($img_url)) {
        $markup = sprintf('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img class="avatar user-thumb" src="%1$s" alt="%2$s"></div></div></div>', $img_url, $img_alt);
    }
    return $markup;
}

function get_page_slug($id, $default = null)
{
    return Page::where('id', $id)->first()->slug ?? $default;
}

function get_navbar_style()
{
    $fallback = get_static_option('global_navbar_variant');
    if (request()->routeIs('frontend.dynamic.page')) {
        $page_info = Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;
    } elseif (request()->routeIs('homepage')) {
        $page_info = Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->navbar_variant : $fallback;
    }
    return $fallback;
}

function get_footer_style()
{
    $fallback = get_static_option('global_footer_variant');
    if (request()->routeIs('frontend.dynamic.page')) {
        $page_info = Page::where(['slug' => request()->path()])->first();
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;
    } elseif (request()->routeIs('homepage')) {
        $page_info = Page::find(get_static_option('home_page'));
        return !is_null($page_info) ? $page_info->footer_variant : $fallback;
    }
    return $fallback;
}

function render_site_title($title)
{
    $site_title = get_static_option('site_title');
    return <<<HTML
    <title> {$title} - {$site_title} </title>
HTML;
}

function render_site_meta()
{

    $user_lang = LanguageHelper::user_lang_slug();
    $site_tags = get_static_option('site_meta_tags');
    $site_desc =  get_static_option('site_meta_description');
    $site_og_meta_title =  get_static_option('og_meta_title');
    $site_og_meta_description =  get_static_option('og_meta_description');
    $site_og_meta_site_name =  get_static_option('og_meta_site_name');
    $site_og_meta_url =  get_static_option('og_meta_url');
    $site_og_meta_image =  render_og_meta_image_by_attachment_id(get_static_option('og_meta_image'));
    $site_og_meta_image_twitter = render_twitter_meta_image_by_attachment_id(get_static_option('og_meta_image'));
    $website_url = \URL::current();

    return <<<HTML
<!-- Primary Meta Tags -->
<meta name="title" content="{$site_og_meta_title}">
<meta name="description" content="{$site_desc}">
<meta name="keywords" content="{$site_tags}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{$website_url}">
<meta property="og:title" content="{$site_og_meta_title}">
<meta property="og:description" content="{$site_desc}">
{$site_og_meta_image}

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{$website_url}">
<meta property="twitter:title" content="{$site_og_meta_title}">
<meta property="twitter:description" content="{$site_desc}">
{$site_og_meta_image_twitter}
HTML;
}

function render_page_meta_data($blog_post)
{

    $user_lang = LanguageHelper::user_lang_slug();
    $site_url = url('/');

    $meta_title =  $blog_post->meta_data->meta_title;
    $site_tags = $blog_post->meta_data->meta_tags;
    $site_description =  $blog_post->meta_data->meta_description;

    $facebook_meta_tags = $blog_post->meta_data->facebook_meta_tags;
    $facebook_meta_description =  $blog_post->meta_data->facebook_meta_description;
    $facebook_meta_image =  get_attachment_image_by_id($blog_post->meta_data->facebook_meta_image)['img_url'] ?? "";

    $twitter_meta_tags = $blog_post->meta_data->twitter_meta_tags;
    $twitter_meta_description =  $blog_post->meta_data->twitter_meta_description;
    $twitter_meta_image =  get_attachment_image_by_id($blog_post->meta_data->twitter_meta_image)['img_url'] ?? "";


    return <<<HTML
       <meta name="meta_title" content="{$meta_title}">
       <meta name="meta_tags" content="{$site_tags}">
       <meta name="meta_description" content="{$site_description}">
       <!--Facebook-->
       <meta property="og:url"content="{$site_url}" >
       <meta property="og:type"content="{$facebook_meta_tags}" >
       <meta property="og:title"content="{$meta_title}" >
       <meta property="og:description"content="{$facebook_meta_description}" >
       <meta property="og:image"content="{$facebook_meta_image}">
       <!--Twitter-->
       <meta name="twitter:card" content="{$twitter_meta_tags}" >
       <meta name="twitter:site" content="{$site_url}" >
       <meta name="twitter:title" content="{$meta_title}" >
       <meta name="twitter:description" content="$twitter_meta_description">
       <meta name="twitter:image" content="{$twitter_meta_image}">
HTML;
}

function render_page_meta_data_for_service($service_details)
{

    $user_lang = LanguageHelper::user_lang_slug();
    $site_url = route('service.list.details', $service_details->slug);

    $meta_title =  $service_details->metaData->meta_title ?? '';
    $site_tags = $service_details->metaData->meta_tags ?? '';
    $site_description =  $service_details->metaData->meta_description ?? '';

    $facebook_meta_tags = $service_details->metaData->facebook_meta_tags ?? '';
    $facebook_meta_description =  $service_details->metaData->facebook_meta_description ?? '';

    $facebook_meta_image =  get_attachment_image_by_id($service_details->metaData->facebook_meta_image ?? '')['img_url'] ?? '';
    $twitter_meta_tags = $service_details->metaData->twitter_meta_tags ?? '';
    $twitter_meta_description =  $service_details->metaData->twitter_meta_description ?? '';
    $twitter_meta_image =  get_attachment_image_by_id($service_details->metaData->twitter_meta_image ?? '')['img_url'] ?? '';
    $title = $service_details->title;

    return <<<HTML
       <title>{$title}</title>
       <meta name="meta_title" content="{$meta_title}">
       <meta name="meta_tags" content="{$site_tags}">
       <meta name="meta_description" content="{$site_description}">
       <!--Facebook-->
       <meta property="og:url"content="{$site_url}" >
       <meta property="og:type"content="{$facebook_meta_tags}" >
       <meta property="og:title"content="{$meta_title}" >
       <meta property="og:description"content="{$facebook_meta_description}" >
       <meta property="og:image"content="{$facebook_meta_image}">
       <!--Twitter-->
       <meta name="twitter:card" content="{$twitter_meta_tags}" >
       <meta name="twitter:site" content="{$site_url}" >
       <meta name="twitter:title" content="{$meta_title}" >
       <meta name="twitter:description" content="$twitter_meta_description">
       <meta name="twitter:image" content="{$twitter_meta_image}">
HTML;
}

function get_blog_slug_by_page_id($id)
{
    $page_details = Page::find($id);
    return is_null($page_details) ? 'blog' : $page_details->slug;
}

function get_blog_category($data)
{

    $colors = ['text-primary', 'text-danger', 'text-success', 'text-info', 'text-dark'];
    foreach ($data->category_id as $key => $cat) {
        '<span class="' . $colors[random_int(0, 4)] . '">' .
            '. $cat->getTranslation("title", $default_lang, true) .'
            . '</span >';
    }
}

function toastr_success($success)
{
    Toastr::success($success, __('Success!'), ["positionClass" => "toast-top-right", "closeButton" => "true", "progressBar" => "true"]);
}

function toastr_error($success)
{
    Toastr::error($success, __('Error!'), ["positionClass" => "toast-top-right", "closeButton" => "true", "progressBar" => "true"]);
}
function toastr_warning($success)
{
    Toastr::warning($success, __('Warning!'), ["positionClass" => "toast-top-right", "closeButton" => "true", "progressBar" => "true"]);
}


function static_text()
{
    return [
        'book_now_btn' => __('Book Now'),
        'book_appoinment' => __('Book Appointment'),
        'read_more_btn' => __('View Details'),
        'select_category' => __('Select Category'),
        'select_sub_category' => __('Select Subcategory'),

        'select_star' => __('Select Star'),
        'one_star' => __('One Star'),
        'two_star' => __('Two Star'),
        'three_star' => __('Three Star'),
        'four_star' => __('Four Star'),
        'five_star' => __('Five Star'),

        'sort_by' => __('Sort By'),
        'latest_service' => __('Latest Service'),
        'lowest_price' => __('Lowest Price'),
        'highest_price' => __('Highest Price'),

        'all_services_text' => __('All Services'),
        'service' => __('Service'),
        'start_at' => __('Starting at'),
        'become_a_seller' => __('Become A Seller'),

        'get_in_touch' => __('Get In Touch'),
        'your_name' => __('Your Name'),
        'email_address' => __('Email Address'),
        'phone_number' => __('Phone Number'),
        'address' => __('Address'),
        'comments' => __('Comments'),
        'send_message' => __('Send Message'),

        'hover_color_two' => 'style-02',
        'back_to_top_2' => 'style-02',
        'back_to_top_3' => 'style-03',
    ];
}

function getSlugFromReadingSetting($reading_type)
{
    $page_id = get_static_option($reading_type);
    $page = Page::find($page_id);

    if ($page) {
        return $page->slug;
    }
    return null;
}

function getPageDetailsFromSlug($reading_type)
{
    $page_id = get_static_option($reading_type);
    return Page::find($page_id);
}

function ratting_star($ratting)
{
    $icon = "";
    $string = "";
    if ($ratting < 1.5) {
        $string = "<i class='las la-star active'></i><i class='las la-star'></i><i class='las la-star'></i><i class='las la-star'></i><i class='las la-star'></i>";
    } elseif ($ratting >= 1.5 && $ratting < 2) {
        $string  = "<i class='las la-star active'></i><i class='las la-star-half'></i><i class='las la-star'></i><i class='las la-star'></i><i class='las la-star'></i>";
    } elseif ($ratting >= 2 && $ratting < 2.5) {
        $string  = "<i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star'></i><i class='las la-star'></i><i class='las la-star'></i>";
    } elseif ($ratting >= 2.5 && $ratting < 3) {
        $string  = "<i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star'></i><i class='las la-star'></i>";
    } elseif ($ratting >= 3 && $ratting < 3.5) {
        $string  = "<i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star-half'></i><i class='las la-star'></i>";
    } elseif ($ratting >= 3.5 && $ratting < 4) {
        $string  = "<i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star'></i>";
    } elseif ($ratting >= 4 && $ratting < 4.5) {
        $string  = "<i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star-half'></i>";
    } elseif ($ratting >= 4.5 && $ratting <= 5) {
        $string  = "<i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i><i class='las la-star active'></i>";
    }

    return $string;
}

function moduleExists($name)
{
    $module_status = json_decode(file_get_contents(__DIR__ . '/../../modules_statuses.json'));
    return property_exists($module_status, $name) ? $module_status->$name : false;
}

function subscriptionModuleExistsAndEnable($name)
{
    $module_status = json_decode(file_get_contents(__DIR__ . '/../../modules_statuses.json'));

    $commission_type = \Illuminate\Support\Facades\Cache::remember('admin_commission_data', 60 * 60 * 24, function () {
        $AdminCommission =  \App\AdminCommission::first();
        return optional($AdminCommission)->system_type;
    });

    if ($name === 'Subscription' && $commission_type === 'commission') {
        return false;
    }

    return property_exists($module_status, $name) ? $module_status->$name : false;
}

function wrapped_id($id)
{
    return Str::random(30) . $id . Str::random(30);
}

function commission_amount($price, $individual_commission, $commission_type, $commission_charge)
{
    if ($individual_commission) {
        $commission_amount = $individual_commission->admin_commission_type == 'fixed' ? $individual_commission->admin_commission_charge : ($price * $individual_commission->admin_commission_charge / 100);
    } else {
        $commission_amount = $commission_type == 'fixed' ? $commission_charge : ($price * $commission_charge / 100);
    }
    return $commission_amount;
}

function transaction_amount($price, $transaction_type, $transaction_charge)
{
    return $transaction_type == 'fixed' ? $transaction_charge : ($price * $transaction_charge / 100);
}

//admin notification
function notificationToAdmin($identity, $user_id, $type, $msg)
{
    AdminNotification::create([
        'identity' => $identity,
        'user_id' => $user_id,
        'type' => $type,
        'message' => $msg,
    ]);
}

function freelancer_notification($identity, $freelancer_id, $type, $msg)
{
    $last_notification = FreelancerNotification::create([
        'identity' => $identity,
        'freelancer_id' => $freelancer_id,
        'type' => $type,
        'message' => $msg
    ]);

    $freelancer = User::where('id', $freelancer_id)->first();
    $notificationBody = [
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        'title' => __('', [
            "message" => $last_notification->message
        ]),
        'id' => $last_notification->id,
        'identity' => $identity,
        'body' => $last_notification->message,
        'description' => '',
        'type' => $type,
        'sound' => 'default',
        'fcm_device' => ''
    ];

    try {
        if ($freelancer->firebase_device_token) {
            $credentialsPath = storage_path('app/firebase/firebase_credentials.json');

            // Load the credentials from the JSON file
            $jsonCredentials = file_get_contents($credentialsPath);
            $credentials = json_decode($jsonCredentials, true);

            // Convert to JSON
            $jsonCredentials = json_encode($credentials);

            // Initialize Firebase Admin SDK
            $factory = (new Factory)->withServiceAccount($jsonCredentials);
            $messaging = $factory->createMessaging();

            // Construct the message
            $message = CloudMessage::new()
                //                ->withNotification(Notification::create($last_notification->type, $last_notification->message))
                ->withData($notificationBody);
            $response = $messaging->sendMulticast($message, $freelancer?->firebase_device_token);
        }
    } catch (\Exception $e) {
    }
}

function client_notification($identity, $client_id, $type, $msg)
{
    $last_notification = ClientNotification::create([
        'identity' => $identity,
        'client_id' => $client_id,
        'type' => $type,
        'message' => $msg
    ]);

    $client_for_device = User::where('id', $client_id)->first();
    $notificationBody = [
        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        'title' => __('', [
            "message" => $last_notification->message
        ]),
        'id' => $last_notification->id,
        'identity' => $identity,
        'body' => $last_notification->message,
        'description' => '',
        'type' => $type,
        'sound' => 'default',
        'fcm_device' => ''
    ];

    try {
        if ($client_for_device->firebase_device_token) {
            $credentialsPath = storage_path('app/firebase/firebase_credentials.json');

            // Load the credentials from the JSON file
            $jsonCredentials = file_get_contents($credentialsPath);
            $credentials = json_decode($jsonCredentials, true);

            // Convert to JSON
            $jsonCredentials = json_encode($credentials);

            // Initialize Firebase Admin SDK
            $factory = (new Factory)->withServiceAccount($jsonCredentials);
            $messaging = $factory->createMessaging();

            // Construct the message
            $message = CloudMessage::new()
                //                ->withNotification(Notification::create($last_notification->type, $last_notification->message))
                ->withData($notificationBody);
            $response = $messaging->sendMulticast($message, $client_for_device?->firebase_device_token);
        }
    } catch (\Exception $e) {
    }
}

function getLastOrderId($order_id)
{
    $random_order_id_1 = Str::random(30);
    $random_order_id_2 = Str::random(30);
    $new_order_id = $random_order_id_1 . $order_id . $random_order_id_2;
    return $new_order_id;
}

function project_rating($project_id)
{
    $project_complete_orders = Order::select('id', 'identity', 'status')->where('identity', $project_id)->where('status', 3)->get();
    $project_complete_order_count = $project_complete_orders->count();

    $count = 0;
    $rating_count = 0;
    $total_rating = 0;
    foreach ($project_complete_orders as $order) {
        $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
        if ($rating) {
            $total_rating = $total_rating + $rating->rating;
            $count = $count + 1;
            $rating_count = $rating_count + 1;
        }
    }


    $avg_rating = $count > 0 ? $total_rating / $count : 0;

    if ($project_complete_order_count >= 1 && $avg_rating >= 1) {
        $string = '<div class="single-project-content-review">
                        <span class="single-project-content-review-icon"> <i class="fa-solid fa-star"></i> </span>
                        <span class="single-project-content-review-rating">' . round($avg_rating, 1) . '(' . $rating_count . ') </span>
                   </div>
                     <a href="#/" class="single-project-orderCompleted"> ' . $project_complete_order_count . ' ' . __("Orders Completed") . ' </a>';
    } else if ($project_complete_order_count >= 1) {
        $string = '<div class="single-project-content-review">
                        <span class="single-project-content-review-rating">' . __("No Review") . '</span>
                   </div>
                    <a href="#/" class="single-project-orderCompleted"> ' . $project_complete_order_count . ' ' . __("Orders Completed") . ' </a>';
    } else if ($project_complete_order_count < 1 && $avg_rating < 1) {
        $string = '<div class="single-project-content-review">
                        <span class="single-project-content-review-rating">' . __("No Review") . '</span>
                   </div>
                    <a href="#/" class="single-project-orderCompleted">' . __("No Order") . ' </a>';
    }
    return $string;
}

function freelancer_rating($freelancer_id, $header = null)
{

    $complete_orders = Order::select('id', 'identity', 'status')->where('freelancer_id', $freelancer_id)->where('status', 3)->get();
    $complete_orders_count = $complete_orders->count();


    $count = 0;
    $rating_count = 0;
    $total_rating = 0;
    foreach ($complete_orders as $order) {
        $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
        if ($rating) {
            $total_rating = $total_rating + $rating->rating;
            $count = $count + 1;
            $rating_count = $rating_count + 1;
        }
    }


    $avg_rating = $count > 0 ? $total_rating / $count : 0;

    $string = '';
    if ($header == null) {
        if ($complete_orders_count >= 1 && $avg_rating >= 1) {
            $string = '<div class="jobFilter-proposal-author-contents-review-flex">
                        <span class="jobFilter-proposal-author-contents-review-icon"><i class="fas fa-star"></i></span>
                        <p class="jobFilter-proposal-author-contents-review-para">' . round($avg_rating, 1) . ' <span>(' . $rating_count . ')</span> </p>
                    </div>
                    <a href="#/" class="single-project-orderCompleted"> ' . $complete_orders_count . ' ' . __("Orders Completed") . '</a>';
        } elseif ($avg_rating >= 1) {
            $string = '<a href="#/" class="single-project-orderCompleted"> ' . $complete_orders_count . ' ' . __("Orders Completed") . '</a>';
        }
        return $string;
    } else {
        return round($avg_rating, 1);
    }
}

function freelancer_rating_for_profile_details_page($freelancer_id)
{

    $complete_orders = Order::select('id', 'identity', 'status')->where('freelancer_id', $freelancer_id)->where('status', 3)->get();
    $complete_orders_count = $complete_orders->count();


    $count = 0;
    $rating_count = 0;
    $total_rating = 0;
    foreach ($complete_orders as $order) {
        $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
        if ($rating) {
            $total_rating = $total_rating + $rating->rating;
            $count = $count + 1;
            $rating_count = $rating_count + 1;
        }
    }

    $avg_rating = $count > 0 ? $total_rating / $count : 0;

    if ($complete_orders_count >= 1 && $rating_count >= 1) {

        $string = ' <div class="single-project-content-review mt-2">
                           <span class="single-project-content-review-icon">
                               <i class="fa-solid fa-star"></i>
                           </span>
                           <span class="single-project-content-review-rating">' . round($avg_rating, 1.) . '(' . $rating_count . ')</span>
                    </div>
                     ';
    } else {
        $string = '';
    }
    return $string;
}

function freelancer_rating_for_job_details_page($freelancer_id)
{

    $complete_orders = Order::select('id', 'identity', 'status')->where('freelancer_id', $freelancer_id)->where('status', 3)->get();
    $complete_orders_count = $complete_orders->count();


    $count = 0;
    $rating_count = 0;
    $total_rating = 0;
    foreach ($complete_orders as $order) {
        $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
        if ($rating) {
            $total_rating = $total_rating + $rating->rating;
            $count = $count + 1;
            $rating_count = $rating_count + 1;
        }
    }


    $avg_rating = $count > 0 ? $total_rating / $count : 0;


    if ($complete_orders_count >= 1) {
        $string = '<span class="jobFilter-proposal-author-contents-review-icon"><i class="fas fa-star"></i></span>
                   <p class="jobFilter-proposal-author-contents-review-para">' . round($avg_rating, 1) . ' <span>(' . $rating_count . ')</span></p>';
    } else {
        $string = '';
    }
    return $string;
}

function freelancer_complete_order_count($freelancer_id = null)
{
    $order_count = Order::where('freelancer_id', $freelancer_id)->where('status', 3)->count();
    if ($order_count >= 1) {
        $string = '<a href="#/" class="jobFilter-proposal-author-contents-jobs">' . $order_count . ' ' . __("Jobs Completed") . ' </a>';
    } else {
        $string = '';
    }
    return $string;
}

function client_complete_order_count($client_id = null)
{
    return Order::where('freelancer_id', $client_id)->where('status', 3)->count();
}

function freelancer_skill_match_with_job_skill($freelancer_id = null, $job_id = null)
{
    $freelancer_skills_string = UserSkill::select('skill')->where('user_id', $freelancer_id)->first();
    $freelancer_skills_array = explode(', ', $freelancer_skills_string->skill ?? '');

    $job_skill = JobPost::with(['job_skills'])->where('id', $job_id)->first();
    $job_skills_count = $job_skill->job_skills->count();
    if ($job_skills_count > 0) {
        $calculate_percentage = round(100 / $job_skills_count, 2);
    } else {
        $calculate_percentage = 0;
    }
    $total_match_percentage = 0;

    foreach ($job_skill->job_skills as $skill) {
        if (in_array($skill->skill, $freelancer_skills_array)) {
            $total_match_percentage = $total_match_percentage + $calculate_percentage;
        }
    }

    $string = '';
    if ($total_match_percentage >= 1) {
        $string = '<span class="myJob-wrapper-single-match">' . round($total_match_percentage, 0) . ' %' . __("Match") . '</span>';
    }

    return $string;
}


function freelancer_level($freelancer_id, $call_from_telent_page = null)
{

    //get evel with level rules
    $levels = \Modules\FreelancerLevel\Entities\FreelancerLevel::with('level_rule')
        ->whereHas('level_rule')
        ->where('status', 1)
        ->get();

    $current_time = Carbon\Carbon::now();
    $current_time = $current_time->toDateTimeString();
    $freelancer_details = \App\Models\User::select('id', 'created_at')->where('id', $freelancer_id)->first();
    $diff_in_days = $freelancer_details->created_at->diffInDays($current_time);

    //get freelancer criteria for level
    $total_order = Order::where('freelancer_id', $freelancer_id)->where('status', 3)->count();
    $total_earnings = Order::where('freelancer_id', $freelancer_id)->where('status', 3)->sum('payable_amount');
    $avg_rating = freelancer_rating_for_level($freelancer_id);

    foreach ($levels as $level) {
        if ($level->level_rule->period >= 1 && $level->level_rule->period < 3) {
            if ($diff_in_days >= 30 && $diff_in_days < 90) {
                if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                    if ($call_from_telent_page == 'talent') {
                        $freelancer_level = '<div class="level-badge-wrapper">' . render_image_markup_by_attachment_id($level->image) . ' ' . '<span class="badge-title">' . $level->level . '</span></div>';
                    } else {
                        $freelancer_level = '(' . $level->level . ')';
                    }
                }
            }
        } elseif ($level->level_rule->period >= 3 && $level->level_rule->period < 6) {
            if ($diff_in_days >= 90 && $diff_in_days < 180) {
                if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                    if ($call_from_telent_page == 'talent') {
                        $freelancer_level = '<div class="level-badge-wrapper">' . render_image_markup_by_attachment_id($level->image) . ' ' . '<span class="badge-title">' . $level->level . '</span></div>';
                    } else {
                        $freelancer_level = '(' . $level->level . ')';
                    }
                }
            }
        } elseif ($level->level_rule->period >= 6 && $level->level_rule->period < 9) {
            if ($diff_in_days >= 180 && $diff_in_days < 270) {
                if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                    if ($call_from_telent_page == 'talent') {
                        $freelancer_level = '<div class="level-badge-wrapper">' . render_image_markup_by_attachment_id($level->image) . ' ' . '<span class="badge-title">' . $level->level . '</span></div>';
                    } else {
                        $freelancer_level = '(' . $level->level . ')';
                    }
                }
            }
        } elseif ($level->level_rule->period >= 9 && $level->level_rule->period < 12) {
            if ($diff_in_days >= 270 && $diff_in_days < 360) {
                if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                    if ($call_from_telent_page == 'talent') {
                        $freelancer_level = '<div class="level-badge-wrapper">' . render_image_markup_by_attachment_id($level->image) . ' ' . '<span class="badge-title">' . $level->level . '</span></div>';
                    } else {
                        $freelancer_level = '(' . $level->level . ')';
                    }
                }
            }
        } elseif ($level->level_rule->period >= 12) {
            if ($diff_in_days >= 360) {
                if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                    if ($call_from_telent_page == 'talent') {
                        $freelancer_level = '<div class="level-badge-wrapper">' . render_image_markup_by_attachment_id($level->image) . ' ' . '<span class="badge-title">' . $level->level . '</span></div>';
                    } else {
                        $freelancer_level = '(' . $level->level . ')';
                    }
                }
            }
        }
    }
    return $freelancer_level ?? '';
}

function freelancer_level_api($freelancer_id, $call_from_telent_page = null)
{
    if (moduleExists('FreelancerLevel')) {
        //get level with level rules
        $levels = \Modules\FreelancerLevel\Entities\FreelancerLevel::with('level_rule')
            ->whereHas('level_rule')
            ->where('status', 1)
            ->get();

        $current_time = Carbon\Carbon::now();
        $current_time = $current_time->toDateTimeString();
        $freelancer_details = \App\Models\User::select('id', 'created_at')->where('id', $freelancer_id)->first();
        $diff_in_days = $freelancer_details->created_at->diffInDays($current_time);

        //get freelancer criteria for level
        $total_order = Order::where('freelancer_id', $freelancer_id)->where('status', 3)->count();
        $total_earnings = Order::where('freelancer_id', $freelancer_id)->where('status', 3)->sum('payable_amount');
        $avg_rating = freelancer_rating_for_level($freelancer_id);

        foreach ($levels as $level) {
            if ($level->level_rule->period >= 1 && $level->level_rule->period < 3) {
                if ($diff_in_days >= 30 && $diff_in_days < 90) {
                    if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                        $freelancer_level = ucfirst($level->level);
                        $level_img = get_attachment_url_only($level->image);
                    }
                }
            } elseif ($level->level_rule->period >= 3 && $level->level_rule->period < 6) {
                if ($diff_in_days >= 90 && $diff_in_days < 180) {
                    if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                        $freelancer_level = ucfirst($level->level);
                        $level_img = get_attachment_url_only($level->image);
                    }
                }
            } elseif ($level->level_rule->period >= 6 && $level->level_rule->period < 9) {
                if ($diff_in_days >= 180 && $diff_in_days < 270) {
                    if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                        $freelancer_level = ucfirst($level->level);
                        $level_img = get_attachment_url_only($level->image);
                    }
                }
            } elseif ($level->level_rule->period >= 9 && $level->level_rule->period < 12) {
                if ($diff_in_days >= 270 && $diff_in_days < 360) {
                    if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                        $freelancer_level = ucfirst($level->level);
                        $level_img = get_attachment_url_only($level->image);
                    }
                }
            } elseif ($level->level_rule->period >= 12) {
                if ($diff_in_days >= 360) {
                    if ($total_order >= $level?->level_rule?->complete_order && $total_earnings >= $level?->level_rule?->earning && $avg_rating >= $level?->level_rule?->avg_rating) {
                        $freelancer_level = ucfirst($level->level);
                        $level_img = get_attachment_url_only($level->image);
                    }
                }
            }
        }
    }
    return [
        'freelancer_level' => $freelancer_level ?? null,
        'level_img' => $level_img ?? null
    ];
}


//freelancer rating for level
function freelancer_rating_for_level($freelancer_id)
{

    $complete_orders = Order::select('id', 'identity', 'status')
        ->where('freelancer_id', $freelancer_id)
        ->where('status', 3)
        ->get();
    $complete_orders_count = $complete_orders->count();


    $count = 0;
    $rating_count = 0;
    $total_rating = 0;
    foreach ($complete_orders as $order) {
        $rating = Rating::where('order_id', $order->id)->where('sender_type', 1)->first();
        if ($rating) {
            $total_rating = $total_rating + $rating->rating;
            $count = $count + 1;
            $rating_count = $rating_count + 1;
        }
    }

    $avg_rating = $count > 0 ? $total_rating / $count : 0;

    if ($complete_orders_count >= 1 && $rating_count >= 1) {
        $string = round($avg_rating, 1);
    } else {
        $string = '';
    }
    return $string;
}

function payment_gateway_list_for_api()

{
    $all_gateways = ['wallet', 'paypal', 'manual_payment', 'mollie', 'paytm', 'stripe', 'razorpay', 'flutterwave', 'paystack', 'marcadopago', 'instamojo', 'cashfree', 'payfast', 'midtrans', 'squareup', 'cinetpay', 'paytabs', 'billplz', 'zitopay', 'sitesway', 'toyyibpay', 'authorize_dot_net', 'iyzipay', 'pagali'];
    return $all_gateways;
}

function render_frontend_cloud_image_if_module_exists($path = '', $load_from = 0)
{
    $ena_dis_front_CDN = get_static_option('front_cdn_enable_disable') ?? '';

    if (Storage::getDefaultDriver() == 's3') {
        $cloudfrontCDN = get_static_option('aws_url') ?? '';
        if ($ena_dis_front_CDN != 'enable') {
            return Storage::renderUrl($path, load_from: $load_from);
        } else {
            return $cloudfrontCDN . '/' . $path;
        }
    }
    if (Storage::getDefaultDriver() == 'wasabi') {
        return Storage::renderUrl($path, load_from: $load_from);
    }
    if (Storage::getDefaultDriver() == 'cloudFlareR2') {
        return Storage::renderUrl($path, load_from: $load_from);
        return Storage::renderUrl($path, load_from: $load_from);
    }
}

function add_frontend_cloud_image_if_module_exists($upload_folder = '', $image = '', $imageName = '', $public_or_private = '')
{
    return Storage::putFileAs($upload_folder, $image, $imageName, $public_or_private);
}

function delete_frontend_cloud_image_if_module_exists($path = '')
{
    $driver = get_static_option('storage_driver');
    if (in_array($driver, ['wasabi', 's3', 'cloudFlareR2'])) {
        return Storage::disk($driver)->delete($path);
    }
}
