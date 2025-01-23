<?php

use Modules\Blog\Http\Controllers\Frontend\BlogController;


//backend routes
Route::group(['as'=>'admin.','prefix'=>'admin/blog','middleware' => ['auth:admin','setlang']],function(){
        Route::controller(\Modules\Blog\Http\Controllers\Admin\BlogController::class)->group(function () {
            Route::get('all','all_blog')->name('blog.all')->permission('blog-list');
            Route::get('create','create')->name('blog.create')->permission('blog-add');
            Route::post('store','store')->name('blog.store')->permission('blog-add');
            Route::get('edit/{id}','edit')->name('blog.edit')->permission('blog-edit');
            Route::post('update/{id}','update')->name('blog.update')->permission('blog-edit');
            Route::post('delete/{id}','destroy')->name('blog.destroy')->permission('blog-delete');
            Route::get('paginate/data', 'pagination')->name('blog.paginate.data');
            Route::get('search/blog', 'search_blog')->name('blog.search');
    });
});


//frontend routes
Route::group(['middleware' => ['globalVariable', 'maintains_mode','setlang']], function () {
    Route::controller(BlogController::class)->group(function(){
        Route::get('blogs/all', 'blog')->name('blog.all');
        Route::get('blogs/all/pagination', 'pagination')->name('blog.pagination');
        Route::get('blogs/filter', 'blog_filter')->name('blog.filter');
        Route::get('blogs/{slug}', 'blog_details')->name('blog.details');
    });
});

