<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
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
    return redirect()->route('home');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'index')->name('home');
    Route::get('/home/profile/{user}', 'profile')->name('home.profile');
    Route::get('/home/product/{product}', 'product')->name('home.product');
    Route::get('/home/pengajuan-reseller', 'pengajuanReseller')->name('home.pengajuan-reseller');
    Route::put('/home/mengajukan-reseller/{user}', 'pengajuanReseller')->name('pengajuan');
});

Route::controller(OwnerController::class)->group(function () {
    Route::get('/owner', 'index')->name('owner');
    Route::get('/owner/profile', 'profile')->name('owner.profile');
    Route::get('/owner/laporan-penjualan', 'salesReport')->name('owner.report');
});

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin', 'index')->name('admin');
    Route::get('/admin/invoices', 'invoice')->name('admin.invoices');
    Route::get('/admin/invoice/{invoice}', 'showInvoice')->name('admin.detail.invoice');
    Route::get('/admin/profile/{user}', 'show')->name('admin.profile');
    Route::get('/admin/edit/{user}', 'edit')->name('admin.edit');
    Route::get('/admin/laporan-penjualan', 'salesReport')->name('admin.sales-report');

    Route::put('/admin/edit/{user}', 'update')->name('admin.update');
    Route::put('/admin/order/update/{order}', 'orderUpdate')->name('admin.order.update');
});

Route::controller(DriverController::class)->group(function () {
    Route::get('/driver', 'index')->name('driver');
    Route::get('/driver/profile', 'profile')->name('driver.profile');
    Route::get('/driver/invoice/{invoice}', 'invoice')->name('driver.invoice');
    Route::post('/driver/confirm-recive/{invoice}', 'store')->name('drive.store');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/user/customer', 'customer')->name('customer');
    Route::get('/user/customer/create', 'customerCreate')->name('customer.create');
    Route::get('/user/show/{user}', 'show')->name('user.show');
    Route::get('/user/edit/{user}', 'edit')->name('user.edit');
    Route::get('/user/customer/update/{user}', 'update')->name('customer.update');
    Route::get('/user/customer/request-upgrade/{user}', 'review')->name('request-upgrade');

    Route::get('/user/reseller', 'reseller')->name('reseller');
    Route::get('/user/reseller/create', 'resellerCreate')->name('reseller.create');

    Route::post('/user/customer', 'customerStore')->name('customer.store');
    Route::post('/user/reseller', 'resellerStore')->name('reseller.store');

    Route::post('/user/mengajukan-reseller/{user}', 'storePengajuan')->name('pengajuan.store');
    Route::put('/user/customer/request-upgrade/{user}', 'acceptRequest')->name('pengajuan.accept');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/cart/mycart', 'index')->name('cart');
    Route::get('/cart/delete/{cart}', 'destroy')->name('cart.delete');
    Route::post('/cart/store/{product}', 'store')->name('cart.store');
    Route::post('/cart/checkout', 'checkout')->name('cart.checkout');
    Route::get('/cart/delete/{cart}', 'delete')->name('cart.delete');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index')->name('product');
    Route::get('/product/create', 'create')->name('product.create');
    Route::get('/product/stock/{product}', 'stock')->name('product.stock');
    Route::get('/product/delete/{product}', 'delete')->name('product.delete');
    Route::get('/product/edit/{product}', 'edit')->name('product.edit');
    Route::get('/product/show/{product}', 'show')->name('product.show');

    Route::put('/product/edit/{product}', 'update')->name('product.update');
    Route::post('/product', 'store')->name('product');
    Route::put('/product/stock/{product}', 'stockStore')->name('stock.store');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category');
    Route::get('/category/create', 'create')->name('category.create');
    Route::get('/category/edit/{category}', 'edit')->name('category.edit');
    Route::get('/category/delete/{category}', 'destroy')->name('category.delete');
    Route::post('/category', 'store')->name('category.store');
    Route::put('/category/edit/{category}', 'update')->name('category.update');
});

Route::controller(CustomAuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::get('/register', 'create')->name('register');
    Route::get('/logout', 'logout')->name('logout');
    Route::post('/login', 'credentials')->name('login');
    Route::post('/register', 'store')->name('register');
});

Route::controller(OrderController::class)->group(function () {
    Route::get('/order', 'index')->name('order');
    Route::get('/order/{order}', 'show')->name('order.show');
    Route::get('/order/delete/{order}', 'delete')->name('order.delete');
    Route::post('/order/checkout', 'checkout')->name('order.checkout');
    Route::post('/order/store', 'storeToOrder')->name('StoreOrder');
    Route::post('/cart/store', 'storeToCart')->name('StoreCart');
    Route::put('/admin/order/update-status/{invoice}', 'updateStatus')->name('admin-order.update-status');
});

Route::controller(InvoiceController::class)->group(function () {
    Route::get('/invoice', 'index')->name('invoice');
    Route::get('/invoice/{invoice}', 'show')->name('invoice.show');
    Route::get('/invoice/order/{invoice}', 'invoice')->name('invoice.order');
    Route::get('/invoice/edit/{invoice}', 'edit')->name('invoice.edit');
    Route::put('/invoice/update/{invoice}', 'update')->name('invoice.update');
});

Route::controller(AreaController::class)->group(function () {
    Route::get('/area', 'index')->name('area');
    Route::get('/area/create', 'create')->name('area.create');
    Route::get('/area/{area}', 'edit')->name('area.edit');
    Route::get('/area/delete/{area}', 'destroy')->name('area.delete');
    Route::post('/area/store', 'store')->name('area.store');
    Route::put('/area/edit/{area}', 'update')->name('area.update');
});

Route::controller(ShippingController::class)->group(function () {
    Route::get('/pengiriman', 'index')->name('pengiriman');
});

Route::controller(NotificationController::class)->group(function () {
    Route::get('/notification', 'index')->name('notification');
});

Route::post('/send-whatsapp-message', [WhatsAppController::class, 'sendWhatsAppMessage'])->name('send.whatsapp');
