<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\SettingsController;

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

Route::get('/fm', function () {
    return view('fm');
})->name('fm');



Route::group(['middleware'=>'notlogin'],function(){




   

Route::get('/',function()   {
    return view('brands');
})->name('brands');

Route::get('/clients',function()   {
    return view('clients');
})->name('clients');

Route::get('/departments',function()   {
    return view('departments');
})->name('departments');

Route::get('/expense',function()   {
    return view('expense');
})->name('expense');

Route::get('/orders',function()   {
    return view('orders');
})->name('orders');

Route::get('/planner',function()   {
    return view('planner');
})->name('planner');

Route::get('/positions',function()   {
    return view('positions');
})->name('positions');


Route::get('/products',function()   {
    return view('products');
})->name('products');

Route::get('/staff',function()   {
    return view('staff');
})->name('staff');

Route::get('/suppliers',function()   {
    return view('suppliers');
})->name('suppliers');




Route::post('/submit-brands','App\Http\Controllers\brandsController@send')->name('submit_brands');
Route::post('/submit-clients','App\Http\Controllers\clientsController@send')->name('submit_clients');
Route::post('/submit-departments','App\Http\Controllers\departmentsController@send')->name('submit_departments');
Route::post('/submit-expense','App\Http\Controllers\expenseController@send')->name('submit_expense');
Route::post('/submit-orders','App\Http\Controllers\ordersController@send')->name('submit_orders');
Route::post('/submit-planner','App\Http\Controllers\plannerController@send')->name('submit_planner');
Route::post('/submit-positions','App\Http\Controllers\positionsController@send')->name('submit_positions');
Route::post('/submit-products','App\Http\Controllers\productsController@send')->name('submit_products');
Route::post('/submit-staff','App\Http\Controllers\staffController@send')->name('submit_staff');
Route::post('/submit-suppliers','App\Http\Controllers\suppliersController@send')->name('submit_suppliers');



Route::get('/','App\Http\Controllers\brandsController@list')->name('brands');
Route::get('/clients','App\Http\Controllers\clientsController@list')->name('clients');
Route::get('/departments','App\Http\Controllers\departmentsController@list')->name('departments');
Route::get('/expense','App\Http\Controllers\expenseController@list')->name('expense');
Route::get('/orders','App\Http\Controllers\ordersController@list')->name('orders');
Route::get('/planner','App\Http\Controllers\plannerController@list')->name('planner');
Route::get('/positions','App\Http\Controllers\positionsController@list')->name('positions');
Route::get('/products','App\Http\Controllers\productsController@list')->name('products');
Route::get('/staff','App\Http\Controllers\staffController@list')->name('staff');
Route::get('/suppliers','App\Http\Controllers\suppliersController@list')->name('suppliers');


Route::get('/delete_brands/{id}','App\Http\Controllers\brandsController@delete')->name('delete_brands');
Route::get('/delete_clients/{id}','App\Http\Controllers\clientsController@delete')->name('delete_clients');
Route::get('/delete_departments/{id}/','App\Http\Controllers\departmentsController@delete')->name('delete_departments');
Route::get('/delete_expense/{id}','App\Http\Controllers\expenseController@delete')->name('delete_expense');
Route::get('/delete_orders/{id}', [OrdersController::class, 'delete'])->name('delete_orders');
Route::get('/delete_planner/{id}','App\Http\Controllers\plannerController@delete')->name('delete_planner');
Route::get('/delete_postiions/{id}','App\Http\Controllers\positionsController@delete')->name('delete_positions');
Route::get('/delete_products/{id}','App\Http\Controllers\productsController@delete')->name('delete_products');
Route::get('/delete_staff/{id}','App\Http\Controllers\staffController@delete')->name('delete_staff');
Route::get('/delete_suppliers/{id}','App\Http\Controllers\suppliersController@delete')->name('delete_suppliers');

Route::get('/edit_brands/{id}','App\Http\Controllers\brandsController@edit')->name('edit_brands');
Route::get('/edit_clients/{id}','App\Http\Controllers\clientsController@edit')->name('edit_clients');
Route::get('/edit_departments/{id}','App\Http\Controllers\departmentsController@edit')->name('edit_departments');
Route::get('/edit_expense/{id}','App\Http\Controllers\expenseController@edit')->name('edit_expense');
Route::get('/edit_orders/{id}','App\Http\Controllers\ordersController@edit')->name('edit_orders');
Route::get('/edit_planner/{id}','App\Http\Controllers\plannerController@edit')->name('edit_planner');
Route::get('/edit_positions/{id}', 'App\Http\Controllers\positionsController@edit')->name('edit_positions');
Route::get('/edit_products/{id}','App\Http\Controllers\productsController@edit')->name('edit_products');
Route::get('/edit_staff/{id}','App\Http\Controllers\staffController@edit')->name('edit_staff');
Route::get('/edit_suppliers/{id}','App\Http\Controllers\suppliersController@edit')->name('edit_suppliers');



Route::post('/update_brands/{id}', 'App\Http\Controllers\brandsController@update')->name('update_brands');
Route::post('/update_clients/{id}','App\Http\Controllers\clientsController@update')->name('update_clients');
Route::post('/update_departments/{id}','App\Http\Controllers\departmentsController@update')->name('update_departments');
Route::post('/update_expense/{id}','App\Http\Controllers\expenseController@update')->name('update_expense');
Route::post('/update_orders/{id}','App\Http\Controllers\ordersController@update')->name('update_orders');
Route::post('/update_planner/{id}','App\Http\Controllers\plannerController@update')->name('update_planner');
Route::post('/update_positions/{id}','App\Http\Controllers\positionsController@update')->name('update_positions');
Route::post('/update_products/{id}','App\Http\Controllers\productsController@update')->name('update_products');
Route::post('/update_staff/{id}','App\Http\Controllers\staffController@update')->name('update_staff');
Route::post('/update_suppliers/{id}','App\Http\Controllers\suppliersController@update')->name('update_suppliers');


Route::get('/accept_orders/{id}','App\Http\Controllers\ordersController@accept')->name('accept_orders');
Route::get('/cancel_orders/{id}','App\Http\Controllers\ordersController@cancel')->name('cancel_orders');

Route::get('/accept_task/{id}','App\Http\Controllers\plannerController@accept')->name('accept_task');
Route::get('/cancel_task/{id}','App\Http\Controllers\plannerController@cancel')->name('cancel_task');
Route::get('logout','App\Http\Controllers\loginController@logout')->name('logout');
Route::get('/stats','App\Http\Controllers\statsController@list')->name('stats');




Route::match(['get', 'post'], '/update_profile/{id}', [ProfileController::class, 'update_profile'])->name('update_profile');
Route::post('/updatepassword', [ProfileController::class, 'updatepassword'])->name('password.update');


Route::get('/delete_selected_brands','App\Http\Controllers\brandsController@delete_selected_brands')->name('delete_selected_brands');
Route::get('/delete_selected_clients','App\Http\Controllers\clientsController@delete_selected_clients')->name('delete_selected_clients');
Route::get('/delete_selected_departments','App\Http\Controllers\departmentsController@delete_selected_departments')->name('delete_selected_departments');
Route::get('/delete_selected_expense','App\Http\Controllers\expenseController@delete_selected_expense')->name('delete_selected_expense');
Route::get('/delete_selected_orders','App\Http\Controllers\ordersController@delete_selected_orders')->name('delete_selected_orders');
Route::get('/delete_selected_planner','App\Http\Controllers\plannerController@delete_selected_planner')->name('delete_selected_planner');
Route::get('/delete_selected_positions','App\Http\Controllers\positionsController@delete_selected_positions')->name('delete_selected_positions');
Route::get('/delete_selected_products','App\Http\Controllers\productsController@delete_selected_products')->name('delete_selected_products');
Route::get('/delete_selected_staff','App\Http\Controllers\staffController@delete_selected_staff')->name('delete_selected_staff');
Route::get('/delete_selected_suppliers','App\Http\Controllers\suppliersController@delete_selected_suppliers')->name('delete_selected_suppliers');



Route::get('/export_brands','App\Http\Controllers\brandsController@export_brands')->name('export_brands');
Route::get('/export_clients','App\Http\Controllers\clientsController@export_clients')->name('export_clients');
Route::get('/export_departments','App\Http\Controllers\departmentsController@export_departments')->name('export_departments');
Route::get('/export_expense','App\Http\Controllers\expenseController@export_expense')->name('export_expense');
Route::get('/export_orders','App\Http\Controllers\ordersController@export_orders')->name('export_orders');
Route::get('/export_planner','App\Http\Controllers\plannerController@export_planner')->name('export_planner');
Route::get('/export_positions','App\Http\Controllers\positionsController@export_positions')->name('export_positions');
Route::get('/export_products','App\Http\Controllers\productsController@export_products')->name('export_products');
Route::get('/export_staff','App\Http\Controllers\staffController@export_staff')->name('export_staff');
Route::get('/export_suppliers','App\Http\Controllers\suppliersController@export_suppliers')->name('export_suppliers');



Route::get('/app', [AppController::class, 'index'])->name('app.index');

Route::get('/manage', 'App\Http\Controllers\manageController@index')->name('manage');
Route::post('/save-settings', 'App\Http\Controllers\manageController@store')->name('save-settings');    



Route::get('/settings/edit', [SettingsController::class, 'edit'])->name('settings.edit');
Route::post('/settings/update/logo', [SettingsController::class, 'updateLogo'])->name('settings.update.logo');
Route::post('/settings/update/contact', [SettingsController::class, 'updateContact'])->name('settings.update.contact');
Route::post('/settings/update/footer', [SettingsController::class, 'updateFooter'])->name('settings.update.footer');

Route::get('/message','App\Http\Controllers\messageController@messageIndex')->name('message');


Route::get('/delete_message/{id}','App\Http\Controllers\messageController@delete_message')->name('delete_message');
Route::get('/accept_message/{id}','App\Http\Controllers\messageController@accept_message')->name('accept_message');
Route::get('/cancel_message/{id}','App\Http\Controllers\messageController@cancel_message')->name('cancel_message');


Route::get('/admin','App\Http\Controllers\adminController@adminIndex')->name('admin');
Route::get('/delete_user/{id}','App\Http\Controllers\adminController@delete_user')->name('delete_user');
Route::get('/admin_user/{id}','App\Http\Controllers\adminController@admin_user')->name('admin_user');
Route::get('/unadmin_user/{id}','App\Http\Controllers\adminController@unadmin_user')->name('unadmin_user');

Route::get('/block_user/{id}','App\Http\Controllers\adminController@block_user')->name('block_user');
Route::get('/unblock_user/{id}','App\Http\Controllers\adminController@unblock_user')->name('unblock_user');


Route::get('/edit_user/{id}', 'App\Http\Controllers\adminController@edit_user')->name('edit_user');
Route::post('/update_user/{id}', 'App\Http\Controllers\adminController@update_user')->name('update_user');




Route::match(['get', 'post'], '/search', 'App\Http\Controllers\searchController@search')->name('search');


});


Route::group(['middleware'=>'islogin'],function(){

    Route::get('/register','App\Http\Controllers\registerController@registerIndex')->name('registerIndex');
    Route::post('/register','App\Http\Controllers\registerController@register')->name('register');

    Route::get('/login','App\Http\Controllers\loginController@loginIndex')->name('loginIndex');
    Route::post('/login','App\Http\Controllers\loginController@login')->name('login');

    Route::get('/contact', 'App\Http\Controllers\ContactController@contactIndex')->name('contactIndex');
    Route::post('/contact', 'App\Http\Controllers\ContactController@contact')->name('contact');
});



























