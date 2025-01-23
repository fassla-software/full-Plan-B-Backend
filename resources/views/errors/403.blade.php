
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{get_static_option('site_meta_'.get_user_lang().'_description')}}">
    <meta name="tags" content="{{get_static_option('site_meta_'.get_user_lang().'_tags')}}">
    <title>{{get_static_option('site_'.get_user_lang().'_title')}} - {{get_static_option('site_'.get_user_lang().'_tag_line')}}</title>

    <link href="https://fonts.googleapis.com/css?family=Montserrat:500" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Titillium+Web:700,900" rel="stylesheet">
    {!! render_favicon_by_id(get_static_option('site_favicon')) !!}
    @if(!empty(get_static_option('custom_css_area')))
        <link rel="stylesheet" href="{{asset('assets/frontend/css/dynamic-style.css')}}">
    @endif
    <link rel="stylesheet" href="{{asset('assets/frontend/css/jquery.ihavecookies.css')}}">
    <style>
        :root {
            --main-color-one: {{ get_static_option('main_color_one') ?? '#6176f6' }};
            --main-color-two: {{ get_static_option('main_color_two') ?? '#2bdfff' }};
            --main-color-one-rgb: {{ '97, 118, 246' }};
            --secondary-color: {{get_static_option('secondary_color')}};
            --secondary-color-rgb: {{get_static_option('secondary_color','#ffa500')}};
            --heading-color: {{get_static_option('heading_color','#1D2635')}};
            --paragraph-color: {{get_static_option('paragraph_color','#1D2635')}};
        }
    </style>
    <style>
        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            padding: 0;
            margin: 0;
        }

        .notfound-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            height: 100vh;
        }

        .notfound {
            text-align: center;
            max-width: 500px;
            width: 100%;
            line-height: 1.4;
            padding: 0px 15px;
        }

        .notfound-wrapper-mainTitle {
            font-family: 'Titillium Web', sans-serif;
            font-size: 180px;
            line-height: 1;
            font-weight: 900;
            margin: 0px;
            text-transform: uppercase;
            color: var(--main-color-one);
        }

        .notfound-wrapper-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 26px;
            line-height: 36px;
            color: var(--heading-color);
            margin: 0;
            margin-top: 10px;
        }

        .notfound-wrapper-para {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 0px;
            color: var(--paragraph-color);
            line-height: 26px;
        }

        .notfound-btn {
            font-family: 'Titillium Web', sans-serif;
            display: inline-block;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            border: none;
            background: var(--main-color-one);
            padding: 10px 40px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 1px;
            margin-top: 15px;
            -webkit-transition: 0.2s all;
            transition: 0.2s all;
            line-height: 26px;
        }

        .notfound-btn:hover {
            background: var(--secondary-color);
        }

        @media only screen and (max-width: 767px) {
            .notfound-wrapper-mainTitle {
                font-size: 120px;
            }
        }
    </style>
</head>
    <body>
        <div id="notfound" class="notfound-wrapper">
            <div class="notfound">
                <div class="notfound-404">
                    <h1 class="notfound-wrapper-mainTitle">{{__('403')}}</h1>
                    <h3 class="notfound-wrapper-title">{{__('USER DOES NOT HAVE THE RIGHT PERMISSIONS.')}}</h3>
                    <a href="{{route('homepage')}}" class="notfound-btn">{{__('Back to home')}}</a>
                </div>
            </div>
        </div>
    </body>
</html>
