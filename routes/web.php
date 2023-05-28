<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::resource('home', HomeController::class);
Route::get('/home/profile/{home}', [HomeController::class, 'profile']);

Route::resource('admin', AdminController::class);
Route::get('/admin/user/reseller', [UserController::class, 'reseller'])->name('user.reseller');
Route::get('/admin/user/customer', [UserController::class, 'customer'])->name('user.customer');
Route::get('/admin/user/customer/create', [UserController::class, 'customerCreate'])->name('customer.create');
Route::get('/admin/user/customer/{customer}', [UserController::class, 'customerShow'])->name('user.customer.show');
Route::post('/admin/user/customer/create', [UserController::class, 'customerStore'])->name('customer.create');
Route::resource('product', ProductController::class);

// Route::get('/admin/product', [ProductController::class, 'index'])->name('product.index');
// Route::get('/admin/product/create', [ProductController::class, 'create'])->name('product.create');
// Route::post('/admin/product/create', [ProductController::class, 'store'])->name('product.create');
Route::get('/admin/product/stock/{product}', [ProductController::class, 'stock'])->name('product.stock');
Route::put('/admin/product/stock/{product}', [ProductController::class, 'stockStore'])->name('stock.store');
Route::get('/admin/product/delete/{product}', [ProductController::class, 'delete'])->name('product.delete');
// Route::get('/product', [AdminController::class, 'product'])->name('admin.product');
// Route::get('/product/create', [AdminController::class, 'productCreate'])->name('admin.product-create');
// Route::post('/admin/create/product', [AdminController::class, 'productStore'])->name('admin.product-store');
// Route::resource('product', ProductController::class);
// Route::resource('auth', CustomAuthController::class);

// Custom Authenticaion
Route::get('/login', [CustomAuthController::class, 'index'])->name('login');
Route::post('/login', [CustomAuthController::class, 'credentials'])->name('credentials');
Route::get('/register', [CustomAuthController::class, 'create'])->name('register');
Route::post('/register', [CustomAuthController::class, 'store'])->name('register.store');
Route::get('/logout', [CustomAuthController::class, 'logout'])->name('logout');

// Route Midtrans
Route::get('/item', [OrderController::class, 'item']);
Route::post('/checkout', [OrderController::class, 'checkout']);
Route::post('/invoice/{id}', [OrderController::class, 'invoice']);
