<style>
    :root {
        --main-color-one: {{ get_static_option('main_color_one') ?? '#6176f6' }};
        --main-color-two: {{ get_static_option('main_color_two') ?? '#2bdfff' }};
        --main-color-one-rgb: {{ '97, 118, 246' }};
        --secondary-color: {{get_static_option('secondary_color')}};
        --secondary-color-rgb: {{ '255, 165, 0'}};
        --bg-gradient: {{ 'linear-gradient(90deg, #fef0db 0%, #fefbf6 50%, #ecf8f0 100%)' }};
        --section-bg-base: {{ '#6176f6' }};
        --section-bg-1: {{ '#F7F8FF' }};
        --section-bg-2: {{ '#F5F5F5' }};
        --footer-bg-1: {{ '#020418' }};
        --footer-bg-2: {{ '#1E84FE' }};
        --copyright-bg-1: {{ '#323336' }};
        --border-color: {{ '#EAECF0' }};
        --border-color-2: {{ '#ddd' }};
        --heading-color: {{get_static_option('heading_color','#1D2635')}};
        --paragraph-color: {{get_static_option('paragraph_color','#1D2635')}};
        --body-color: {{get_static_option('body_color','#999')}};
        --white: {{ '#fff' }};
        --active-color: {{ '#00C897' }};
        --active-color-rgb: {{ '0, 200, 151' }};
        --success-color: {{ '#65c18c' }};
        --success-color-rgb: {{ '101, 193, 140' }};
        --danger-color: {{ '#f53a3a' }};
        --danger-color-rgb: {{ '245, 58, 58' }};
        --promo-one: {{ '#e3e1ff' }};
        --promo-two: {{ '#ffe6d3' }};
        --promo-three: {{ '#dbf3ff' }};
        --promo-four: {{ '#efffe6' }};
        --promo-five: {{ '#ffc9c9' }};
        --promo-six: {{ '#ceffda' }};
        --promo-seven: {{ '#b2ccfd' }};
        --promo-eight: {{ '#f0bcff' }};
        --heading-font: {{get_static_option('heading_font_family')}},sans-serif;
        --body-font: {{get_static_option('body_font_family')}},sans-serif;
        --Otomanopee-font: {{get_static_option('section_font_family')}},sans-serif;
        {{----Otomanopee-font: {{ "Aclonica", "sans-serif" }};--}}
        {{----Otomanopee-font: {{ "Otomanopee", "sans-serif" }};--}}
    }
</style>