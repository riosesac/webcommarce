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

Route::get('/', function () {
    return view('welcome');
});

Route::match(['get','post'],'/admin','AdminController@login');

Route::get('/logout','AdminController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=> ['auth']], function(){
	Route::get('/admin/dashboard','AdminController@dashboard');
	Route::get('/admin/settings','AdminController@settings');
	Route::get('/admin/check-pwd','AdminController@chkPassword');
	Route::match(['get','post'],'/admin/update-pwd','AdminController@updatePassword');

	//Category Routes (Admin)
	Route::match(['get','post'],'/admin/add-category', 'CategoryController@addCategory');
	Route::match(['get','post'],'/admin/edit-category/{id}','CategoryController@editCategory');
	Route::match(['get','post'],'/admin/delete-category/{id}','CategoryController@deleteCategory');
	Route::get('/admin/view-categories', 'CategoryController@viewCategories');

	//ProductsRoute (Admin)
	Route::match(['get','post'],'/admin/add-product', 'ProductController@addProduct');
	Route::match(['get','post'],'/admin/edit-product/{id}','ProductController@editProduct');
	Route::match(['get','post'],'/admin/delete-product-image/{id}','ProductController@deleteProductImage');
	Route::match(['get','post'],'/admin/delete-product/{id}','ProductController@deleteProduct');
	Route::get('/admin/view-products', 'ProductController@viewProducts');

	//Product Attribute
	Route::match(['get','post'],'/admin/add-attributes/{id}','ProductController@addAttributes');
	Route::match(['get','post'],'/admin/edit-attributes/{id}','ProductController@editAttributes');
	Route::match(['get','post'],'/admin/add-images/{id}','ProductController@addImages');
	Route::match(['get','post'],'/admin/delete-attribute/{id}','ProductController@deleteAttributes');
	Route::match(['get','post'],'/admin/delete-alt-images/{id}','ProductController@deleteAltImages');
});

//home page
Route::get('/','IndexContraller@index');

//Category / listing page
Route::get('/products/{url}','ProductController@products');

//Halaman Category Detail
Route::get('/product/{id}','ProductController@product');

// ke produk atribut price
Route::get('/get-product-price','ProductController@getProductPrice');