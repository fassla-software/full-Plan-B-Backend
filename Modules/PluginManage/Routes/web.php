<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ------------------------------------------
     LANDLORD ADMIN ROUTES
-------------------------------------------- */
Route::group(['middleware' => ['auth:admin', 'setlang'], 'prefix' => 'admin'],function () {
    Route::get("plugin-manage/all",[\Modules\PluginManage\Http\Controllers\PluginManageController::class,"index"])->name("admin.plugin.manage.all");
    Route::get("plugin-manage/new",[\Modules\PluginManage\Http\Controllers\PluginManageController::class,"add_new"])->name("admin.plugin.manage.new");
    Route::post("plugin-manage/new",[\Modules\PluginManage\Http\Controllers\PluginManageController::class,"store_plugin"]);
    Route::post("plugin-manage/delete",[\Modules\PluginManage\Http\Controllers\PluginManageController::class,"delete_plugin"])->name("admin.plugin.manage.delete");
    Route::post("plugin-manage/status",[\Modules\PluginManage\Http\Controllers\PluginManageController::class,"change_status"])->name("admin.plugin.manage.status.change");
});


//Route::prefix('pluginmanage')->group(function() {
//    Route::get('/', 'PluginManageController@index');
//});
