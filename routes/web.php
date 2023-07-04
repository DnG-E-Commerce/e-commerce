<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
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
    return redirect()->route('us.home');
});

// Route::get('/{page}', '')

Route::controller(HomeController::class)->group(function () {
    // Profile
    Route::get('/us/home', 'index')->name('us.home');
    Route::get('/us/profile', 'profile')->name('us.profile');
    Route::get('/us/profile/edit', 'editProfile')->name('us.edit.profile');
    Route::get('/us/profile/password/change', 'changePassword')->name('us.change.password');
    Route::get('/us/apply-request-reseller', 'requestReseller')->name('us.apply-request-reseller');

    Route::put('/us/profile/update/{user}', 'updateProfile')->name('us.profile.update');
    Route::put('/us/profile/password/update/{user}', 'updatePassword')->name('us.profile.password.update');

    // Other Menu
    Route::get('/us/cart', 'cart')->name('us.cart');
    Route::get('/us/order', 'order')->name('us.order');
    Route::get('/us/notification', 'notification')->name('us.notification');
});

Route::controller(AdminController::class)->group(function () {
    // Only Return View
    Route::get('/su/dashboard', 'index')->name('su.dashboard');
    Route::get('/su/product', 'product')->name('su.product');
    Route::get('/su/category', 'category')->name('su.category');
    Route::get('/su/admin', 'admin')->name('su.admin');
    Route::get('/su/reseller', 'reseller')->name('su.reseller');
    Route::get('/su/customer', 'customer')->name('su.customer');
    Route::get('/su/order', 'order')->name('su.order');
    Route::get('/su/area', 'area')->name('su.area');
    Route::get('/su/sales-graph', 'salesGraph')->name('su.sales-graph');
    Route::get('/su/sales-report', 'salesReport')->name('su.sales-report');
    Route::get('/su/delivery', 'delivery')->name('su.delivery');

    // Profile
    Route::get('/su/profile', 'profile')->name('su.profile');
    Route::get('/su/profile/change-password', 'changePassword')->name('su.profile.change-password');
    Route::get('/su/profile/edit', 'edit')->name('su.profile.edit');

    Route::put('/su/profile/edit/{user}', 'update')->name('su.profile.update');
    Route::put('/su/profile/change-password/{user}', 'updatePassword')->name('su.profile.update-password');
    Route::put('/su/order/update/{order}', 'orderUpdate')->name('admin.order.update');
});

Route::controller(DriverController::class)->group(function () {
    Route::get('/driver/invoice/{invoice}', 'invoice')->name('driver.invoice');
    Route::post('/driver/confirm-recive/{invoice}', 'store')->name('drive.store');
});

Route::controller(UserController::class)->group(function () {
    // Customer
    Route::get('/su/customer/create', 'createCustomer')->name('su.customer.create');
    Route::get('/su/customer/show/{user}', 'show')->name('su.customer.show');
    Route::get('/su/customer/edit/{user}', 'edit')->name('su.customer.edit');
    Route::get('/su/customer/update/{user}', 'update')->name('su.customer.update');
    Route::get('/su/customer/request-upgrade/{user}', 'review')->name('su.customer.request-upgrade');
    Route::post('/su/customer/request-reseller/{user}', 'storeRequest')->name('su.customer.request.store');
    Route::put('/su/customer/request-upgrade/{user}', 'acceptRequest')->name('su.customer.request.accept');

    // Reseller
    Route::get('/su/reseller/create', 'createReseller')->name('su.reseller.create');

    // Admin & Driver
    Route::get('/su/admin-driver/create', 'createAdmin')->name('su.admin-driver.create');

    // Dynamic
    Route::get('/su/{role}/profile/{user}', 'profileUser')->name('su.user.profile');
    Route::post('/su/{role}/store', 'storeUser')->name('su.user.create');
});

Route::controller(CartController::class)->group(function () {
    Route::get('/cart/mycart', 'index')->name('cart');
    Route::get('/cart/delete/{cart}', 'destroy')->name('cart.delete');
    Route::post('/cart/store/{product}', 'store')->name('cart.store');
    Route::post('/cart/checkout', 'checkout')->name('cart.checkout');
    Route::get('/cart/delete/{cart}', 'delete')->name('cart.delete');
});

Route::controller(ProductController::class)->group(function () {
    // Client
    Route::get('/us/product/{product}', 'usDetailProduct')->name('us.product.detail');

    // Admin
    Route::get('/su/product/create', 'create')->name('su.product.create');
    Route::get('/su/product/stock/{product}', 'stock')->name('su.product.stock');
    Route::get('/su/product/delete/{product}', 'delete')->name('su.product.delete');
    Route::get('/su/product/edit/{product}', 'edit')->name('su.product.edit');
    Route::get('/su/product/{product}', 'suDetailProduct')->name('su.product.detail');
    Route::put('/su/product/edit/{product}', 'update')->name('su.product.update');
    Route::post('/su/product', 'store')->name('su.store.product');
    Route::put('/su/product/stock/{product}', 'stockStore')->name('su.stock.store');
});

Route::controller(CategoryController::class)->group(function () {
    // Admin
    Route::get('/su/category/create', 'create')->name('su.category.create');
    Route::get('/su/category/edit/{category}', 'edit')->name('su.category.edit');
    Route::get('/su/category/delete/{category}', 'destroy')->name('su.category.delete');
    Route::post('/su/category', 'store')->name('su.category.store');
    Route::put('/su/category/edit/{category}', 'update')->name('su.category.update');
});

Route::controller(CustomAuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login');
    Route::get('/register', 'create')->name('register');

    Route::get('/login/send-otp/{user}', 'sendOTPFromLogin')->name('login.send-otp');
    Route::get('/email-verification', 'emailVerification')->name('email-verification');

    Route::get('/logout', 'logout')->name('logout');
    Route::post('/login', 'credentials')->name('login');
    Route::post('/register', 'store')->name('register');
    Route::post('/email-verification', 'check')->name('email-verification.check');
});

Route::controller(OrderController::class)->group(function () {
    // Admin
    Route::put('/su/order/update-status/{invoice}', 'suUpdateStatus')->name('su.order.update-status');

    // Client
    Route::get('/us/order/{order}', 'show')->name('order.show');
    Route::get('/us/order/delete/{order}', 'delete')->name('order.delete');
    Route::post('/us/order/checkout', 'checkout')->name('order.checkout');
    Route::post('/us/order/store', 'storeToOrder')->name('StoreOrder');
    Route::post('/us/cart/store', 'storeToCart')->name('StoreCart');
});

Route::controller(InvoiceController::class)->group(function () {
    // Routes Admin
    Route::get('/su/invoice/{invoice}', 'suDetailInvoice')->name('su.invoice.detail');
    Route::get('/su/invoice/confirm-cash/{invoice}', 'confirmCash')->name('su.invoice.confirm-cash');
    Route::get('/su/invoice/print/{invoice}', 'print_pdf')->name('su.invoice.print_pdf');

    // Routes Client
    Route::get('/us/invoice', 'index')->name('us.invoice');
    Route::get('/us/invoice/{invoice}', 'show')->name('us.invoice.show');
    Route::get('/us/invoice/order/{invoice}', 'invoice')->name('us.invoice.order');
    Route::get('/us/invoice/edit/{invoice}', 'edit')->name('us.invoice.edit');
    Route::put('/us/invoice/update/{invoice}', 'update')->name('us.invoice.update');
    Route::get('/us/invoice/recive/{invoice}', 'confirmRecive')->name('us.invoice.recive');
});

Route::controller(AreaController::class)->group(function () {
    Route::get('/su/area/create', 'create')->name('su.area.create');
    Route::get('/su/area/{area}', 'edit')->name('su.area.edit');
    Route::get('/su/area/delete/{area}', 'destroy')->name('su.area.delete');

    Route::post('/su/area/store', 'store')->name('su.area.store');
    Route::put('/su/area/edit/{area}', 'update')->name('su.area.update');
});

Route::post('/send-whatsapp-message', [WhatsAppController::class, 'sendWhatsAppMessage'])->name('send.whatsapp');
