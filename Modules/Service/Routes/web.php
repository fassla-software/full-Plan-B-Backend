<?php

use Illuminate\Support\Facades\Route;

Route::group(['as'=>'admin.','prefix'=>'admin/service','middleware' => ['auth:admin','setlang']],function(){
    // category
    Route::group(['prefix'=>'category'],function(){
        Route::controller(\Modules\Service\Http\Controllers\Backend\CategoryController::class)->group(function () {
            Route::match(['get','post'],'enable-disable-category','enable_disable')->name('category.enable.disable.settings');
            Route::match(['get','post'],'all-category','all_category')->name('category.all')->permission('category-list');
            Route::match(['get','post'],'select-category','select_category')->name('category.select')->permission('category-list');
            Route::post('edit-category/{id?}','edit_category')->name('category.edit')->permission('category-edit');
            Route::post('change-status/{id}','change_status')->name('category.status')->permission('category-status-change');
            Route::post('delete/{id}','delete_category')->name('category.delete')->permission('category-delete');
            Route::post('bulk-action', 'bulk_action_category')->name('category.delete.bulk.action')->permission('category-bulk-delete');
            Route::get('paginate/data', 'pagination')->name('category.paginate.data');
            Route::get('search-category', 'search_category')->name('category.search');
        });
    });

    // sub category
    Route::group(['prefix'=>'subcategory'],function(){
        Route::controller(\Modules\Service\Http\Controllers\Backend\SubCategoryController::class)->group(function () {
            Route::match(['get','post'],'all-subcategory','all_subcategory')->name('subcategory.all')->permission('subcategory-list');
            Route::post('edit-subcategory/{id?}','edit_subcategory')->name('subcategory.edit')->permission('subcategory-edit');
            Route::post('change-status/{id}','change_status')->name('subcategory.status')->permission('subcategory-status-change');
            Route::post('delete/{id}','delete_subcategory')->name('subcategory.delete')->permission('subcategory-delete');
            Route::post('bulk-action', 'bulk_action_subcategory')->name('subcategory.delete.bulk.action')->permission('subcategory-bulk-delete');
            Route::get('paginate/data', 'pagination')->name('subcategory.paginate.data');
            Route::get('search-subcategory', 'search_subcategory')->name('subcategory.search');
        });
    });

});
