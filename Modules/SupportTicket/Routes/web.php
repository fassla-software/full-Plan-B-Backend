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

use Illuminate\Support\Facades\Route;
use Modules\SupportTicket\Http\Controllers\Backend\DepartmentController;
use Modules\SupportTicket\Http\Controllers\Backend\SupportTicketController;
use Modules\SupportTicket\Http\Controllers\Client\ClientTicketController;
use Modules\SupportTicket\Http\Controllers\Freelancer\FreelancerTicketController;


//admin routes
Route::group(['as'=>'admin.','prefix'=>'admin/support-ticket','middleware' => ['auth:admin','setlang']],function() {
    //department
    Route::match(['get', 'post'], '/department', [DepartmentController::class, 'department'])->name('department')->permission('department-list');
    Route::post('edit-department', [DepartmentController::class, 'edit_department'])->name('department.edit')->permission('department-edit');
    Route::post('department-change-status/{id}', [DepartmentController::class, 'change_status'])->name('department.status')->permission('department-status-update');
    Route::post('delete-department/{id}', [DepartmentController::class, 'delete_department'])->name('department.delete')->permission('department-delete');
    Route::post('department-bulk-action', [DepartmentController::class, 'bulk_action'])->name('department.delete.bulk.action')->permission('department-bulk-delete');
    //ticket
    Route::match(['get', 'post'], '/tickets', [SupportTicketController::class, 'ticket'])->name('ticket')->permission('support-ticket-list');
    Route::get('/pagination', [SupportTicketController::class, 'paginate'])->name('ticket.paginate.data');
    Route::post('/search', [SupportTicketController::class, 'search_ticket'])->name('ticket.search');
    Route::post('change-status/{id}', [SupportTicketController::class, 'change_status'])->name('ticket.status')->permission('support-ticket-status-change');
    Route::post('delete-ticket/{id}', [SupportTicketController::class, 'delete_ticket'])->name('ticket.delete')->permission('support-ticket-delete');
    Route::post('bulk-action', [SupportTicketController::class, 'bulk_action'])->name('ticket.delete.bulk.action')->permission('support-ticket-bulk-delete');
    Route::match(['get','post'],'/details/{id}', [SupportTicketController::class, 'ticket_details'])->name('ticket.details')->permission('support-ticket-details');

});

//freelancer routes
Route::group(['prefix'=>'freelancer/support-ticket','as'=>'freelancer.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
    Route::match(['get', 'post'], '/ticket', [FreelancerTicketController::class, 'ticket'])->name('ticket');
    Route::get('/pagination', [FreelancerTicketController::class, 'paginate'])->name('ticket.paginate.data');
    Route::post('/search', [FreelancerTicketController::class, 'search_ticket'])->name('ticket.search');
    Route::match(['get','post'],'/details/{id}', [FreelancerTicketController::class, 'ticket_details'])->name('ticket.details');
});

//clients routes
Route::group(['prefix'=>'client/support-ticket','as'=>'client.','middleware'=>['auth','userEmailVerify','Google2FA','globalVariable', 'maintains_mode','setlang']],function() {
        Route::match(['get', 'post'], '/ticket', [ClientTicketController::class, 'ticket'])->name('ticket');
        Route::get('/pagination', [ClientTicketController::class, 'paginate'])->name('ticket.paginate.data');
        Route::post('/search', [ClientTicketController::class, 'search_ticket'])->name('ticket.search');
        Route::match(['get','post'],'/details/{id}', [ClientTicketController::class, 'ticket_details'])->name('ticket.details');
    });
