<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();
Route::get('/', function () {
    if(Auth::check()) {
		return redirect('/home');
		// Route::get('/permissionList', [App\Http\Controllers\PermissionController::class, 'list']);
    } else {
        return view('auth.login');
    }
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {

    // users
    Route::resource('users', 'UserController');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    // companies
    Route::delete('companies/destroy', 'CompanyController@massDestroy')->name('companies.massDestroy');
    Route::resource('companies', 'CompanyController');
    // branches
    Route::delete('branches/destroy', 'BranchController@massDestroy')->name('branches.massDestroy');
    Route::resource('branches', 'BranchController');
    //roles
    Route::resource('roles', 'RoleController');
    Route::delete('roles/destroy', ['RoleController', 'destroy'])->name('roles.massDestroy');
    //customers
    Route::resource('customers', CustomerController::class);
    Route::delete('customers/destory', [CustomerController::class, 'destroy'])->name('customer.massDestroy');
    //cities
    Route::resource('cities', CityController::class);
    Route::delete('cities/destory', [CityController::class, 'destroy'])->name('cities.massDestroy');
	//units
    Route::resource('units', UnitController::class);
    Route::delete('units/destory', [UnitController::class, 'destroy'])->name('units.massDestroy');
    
	
	// stocks
//    Route::resource('stocks', [StockController::class]);

    Route::resource('purchases', PurchaseController::class);


	Route::resource('/permissions', App\Http\Controllers\PermissionController::class);
	Route::get('/permissionList', [App\Http\Controllers\PermissionController::class, 'list']);
	Route::delete('/permissionDelete', [App\Http\Controllers\PermissionController::class, 'destroy']);



	Route::resource('/customer_types', App\Http\Controllers\Customer_typeController::class);
	Route::get('/customer_typeList', [App\Http\Controllers\Customer_typeController::class, 'list']);
	Route::delete('/customer_typeDelete', [App\Http\Controllers\Customer_typeController::class, 'destroy']);

	Route::resource('/items', App\Http\Controllers\ItemController::class);
	Route::get('/itemList', [App\Http\Controllers\ItemController::class, 'list']);
	Route::delete('/itemDelete', [App\Http\Controllers\ItemController::class, 'destroy']);


	Route::resource('/stocks', App\Http\Controllers\StockController::class);
	Route::get('/stockList', [App\Http\Controllers\StockController::class, 'list']);
	Route::delete('/stockDelete', [App\Http\Controllers\StockController::class, 'destroy']);

	Route::resource('/sells', App\Http\Controllers\SellController::class);
	Route::get('/sellList', [App\Http\Controllers\SellController::class, 'list']);
	Route::delete('/sellDelete', [App\Http\Controllers\SellController::class, 'destroy']);
	Route::post('/fetch_item_unit_detail', [App\Http\Controllers\SellController::class, 'fetch_item_unit_detail']);

	Route::resource('/payment_methods', App\Http\Controllers\Payment_methodController::class);
	Route::get('/payment_methodList', [App\Http\Controllers\Payment_methodController::class, 'list']);
	Route::delete('/payment_methodDelete', [App\Http\Controllers\Payment_methodController::class, 'destroy']);



	Route::resource('/reports', App\Http\Controllers\ReportController::class);
	Route::resource('/vouchers', App\Http\Controllers\VoucherController::class);

	// Route::resource('/amount_types', App\Http\Controllers\Amount_typeController::class);
	// Route::get('/amount_typeList', [App\Http\Controllers\Amount_typeController::class, 'list']);
	// Route::delete('/amount_typeDelete', [App\Http\Controllers\Amount_typeController::class, 'destroy']);


});


