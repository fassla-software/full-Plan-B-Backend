<?php

use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.', 'prefix' => 'admin','middleware' => ['auth:admin','setlang']], function () {
    Route::group(['prefix' => 'dynamic-pages'], function () {
        Route::controller(\Modules\Pages\Http\Controllers\PagesController::class)->group(function () {
            Route::match(['get', 'post'], '/all', 'all_pages')->name('page.all')->permission('page-list');
            Route::match(['get', 'post'], '/add-new', 'add_new_page')->name('page.new')->permission('page-create-new');
            Route::match(['get', 'post'], '/edit-page/{id}', 'edit_page')->name('edit.page')->permission('page-edit');
            Route::post('/delete-single-page/{id}', 'delete_single_page')->name('delete.single.page')->permission('page-delete');
            Route::post('/delete-bulk-page', 'bulk_action')->name('delete.bulk.action.page')->permission('page-delete-bulk-action');
            Route::match(['get', 'post'], '/404-page', '_404_page')->name('page.404')->permission('manage-404-page');
            Route::match(['get', 'post'], '/maintenance-page', 'maintenance_page')->name('page.maintenance')->permission('manage-maintenance-page');
        });
    });

});

