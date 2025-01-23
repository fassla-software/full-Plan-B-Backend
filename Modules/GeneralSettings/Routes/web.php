<?php

use Illuminate\Support\Facades\Route;

Route::group(['as'=>'admin.','prefix'=>'admin','middleware' => ['auth:admin','setlang']],function(){
    Route::group(['prefix'=>'general-settings'],function(){
        Route::controller(\Modules\GeneralSettings\Http\Controllers\GeneralSettingsController::class)->group(function () {
            Route::match(['get','post'],'/reading','reading')->name('general.settings.reading')->permission('reading');
            Route::match(['get','post'],'/site-identity','site_identity')->name('general.settings.site.identity')->permission('site-identity');
            Route::match(['get','post'],'/basic-settings','basic_settings')->name('general.settings.basic')->permission('basic-settings');
            Route::match(['get','post'],'/color-settings','color_settings')->name('general.settings.color')->permission('color-settings');
            Route::match(['get','post'],'/typography-settings','typography_settings')->name('general.settings.typography')->permission('typography-settings');
            Route::post('/typography-settings-single','typography_settings_single')->name('general.settings.typography.single')->permission('typography-settings');
            Route::match(['get','post'],'/seo-settings','seo_settings')->name('general.settings.seo')->permission('seo-settings');
            Route::match(['get','post'],'/third-party-scripts-settings','third_party_scripts_settings')->name('general.settings.third.party.script')->permission('third-party-script-settings');
            Route::match(['get','post'],'/social-login-settings','social_login_settings')->name('general.settings.social.login')->permission('social-login-settings');
            Route::match(['get','post'],'/email-template-settings','email_template_settings')->name('general.settings.email.template')->permission('email-template-settings');
            Route::match(['get','post'],'/smtp-settings','smtp_settings')->name('general.settings.smtp')->permission('smtp-settings');
            Route::post('/test-smtp-settings','test_smtp_settings')->name('general.settings.smtp.test')->permission('smtp-settings');
            Route::match(['get','post'],'/custom-css','custom_css')->name('general.settings.custom.css')->permission('custom-css-settings');
            Route::match(['get','post'],'/custom-js','custom_js')->name('general.settings.custom.js')->permission('custom-js-settings');
            Route::match(['get','post'],'/gdpr-settings','gdpr_settings')->name('general.settings.gdpr')->permission('gdpr-settings');
            Route::match(['get','post'],'/licence-settings','licence_settings')->name('general.settings.licence')->permission('licence-settings');
            Route::match(['get','post'],'/cache-settings','cache_settings')->name('general.settings.cache')->permission('cache-settings');
            Route::match(['get','post'],'/database-upgrade','database_upgrade')->name('general.settings.database.upgrade')->permission('database-upgrade');
            Route::match(['get','post'],'/navbar-global-variant','navbar_global_variant')->name('general.settings.navbar.global.variant')->permission('navbar-global-variant');
            Route::match(['get','post'],'/footer-global-variant','footer_global_variant')->name('general.settings.footer.global.variant')->permission('footer-global-variant');

            //license settings
            Route::get('/license/settings','license_settings')->name('license.settings');
            Route::post('/license/settings','update_license_settings');

            //new auto update features route
            Route::post('/license/setting-verify', 'license_key_generate')->name('license.key.generate');
            Route::get('license/update-check', 'update_version_check')->name('version.check');
            Route::post('license/download-update/{productId}/{tenant}', 'updateDownloadLatestVersion')->name('download.update.files');
            Route::get('license/software-update-setting', 'software_update_check_settings')->name('software.update.settings');
        });
    });

});
