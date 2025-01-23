<?php

use Illuminate\Support\Facades\Route;

Route::group(['as'=>'admin.','prefix'=>'admin/location','middleware' => ['auth:admin','setlang']],function(){
    Route::group(['prefix'=>'country'],function(){
        Route::controller(\Modules\CountryManage\Http\Controllers\CountryController::class)->group(function () {
            Route::match(['get','post'],'all-country','all_country')->name('country.all')->permission('country-list');
            Route::post('edit-country/{id?}','edit_country')->name('country.edit')->permission('country-edit');
            Route::post('change-status/{id}','change_status_country')->name('country.status')->permission('country-status.change');
            Route::post('delete/{id}','delete_country')->name('country.delete')->permission('country-delete');
            Route::post('bulk-action', 'bulk_action_country')->name('country.delete.bulk.action')->permission('country-bulk-delete');

            Route::get('paginate/data', 'pagination')->name('country.paginate.data');
            Route::get('search-country', 'search_country')->name('country.search');

            Route::get('csv/import','import_settings')->name('country.import.csv.settings')->permission('country-csv-file-import');
            Route::post('csv/import','update_import_settings')->name('country.import.csv.update.settings');
            Route::post('csv/import/database','import_to_database_settings')->name('country.import.database');
        });
    });

    Route::group(['prefix'=>'state'],function(){
        Route::controller(\Modules\CountryManage\Http\Controllers\StateController::class)->group(function () {
            Route::match(['get','post'],'all-state','all_state')->name('state.all')->permission('state-list');
            Route::post('edit-state/{id?}','edit_state')->name('state.edit')->permission('state-edit');
            Route::post('change-status/{id}','change_status_state')->name('state.status')->permission('state-status-change');
            Route::post('delete/{id}','delete_state')->name('state.delete')->permission('state-delete');
            Route::post('bulk-action', 'bulk_action_state')->name('state.delete.bulk.action')->permission('state-bulk-delete');

            Route::get('paginate/data', 'pagination')->name('state.paginate.data');
            Route::get('search-state', 'search_state')->name('state.search');

            Route::get('csv/import','import_settings')->name('state.import.csv.settings')->permission('state-csv-file-import');
            Route::post('csv/import','update_import_settings')->name('state.import.csv.update.settings');
            Route::post('csv/import/database','import_to_database_settings')->name('state.import.database');
        });
    });

    Route::group(['prefix'=>'city'],function(){
        Route::controller(\Modules\CountryManage\Http\Controllers\CityController::class)->group(function () {
            Route::match(['get','post'],'all-city','all_city')->name('city.all')->permission('city-list');
            Route::post('edit-city/{id?}','edit_city')->name('city.edit')->permission('city-edit');
            Route::post('change-status/{id}','city_status')->name('city.status')->permission('city-status-change');
            Route::post('delete/{id}','delete_city')->name('city.delete')->permission('city-delete');
            Route::post('bulk-action', 'bulk_action_city')->name('city.delete.bulk.action')->permission('city-bulk-delete');

            Route::get('paginate/data', 'pagination')->name('city.paginate.data');
            Route::get('search-city', 'search_city')->name('city.search');

            Route::get('csv/import','import_settings')->name('city.import.csv.settings')->permission('city-csv-file-import');
            Route::post('csv/import','update_import_settings')->name('city.import.csv.update.settings');
            Route::post('csv/import/database','import_to_database_settings')->name('city.import.database');
        });
    });

});
