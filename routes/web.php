<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Статические страницы
Route::view('/about', 'pages.about')->name('pages.about');
Route::view('/delivery', 'pages.delivery')->name('pages.delivery');
Route::view('/vr-games', 'pages.vr-games')->name('pages.vr-games');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::resource('catalog', CatalogController::class);
Route::get('/products/{product:slug}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');

Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/add', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove', [CompareController::class, 'remove'])->name('compare.remove');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');









// Dashboard:
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard/{tab?}', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/products', [AdminController::class, 'addProduct'])->name('product.add');
    Route::put('/products/{id}', [AdminController::class, 'editProduct'])->name('product.edit');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('product.delete');
    
    Route::post('/categories', [AdminController::class, 'addCategory'])->name('category.add');
    Route::put('/categories/{id}', [AdminController::class, 'editCategory'])->name('category.edit');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('category.delete');
    
    Route::post('/users/{id}/toggle', [AdminController::class, 'toggleAdmin'])->name('user.toggle');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
});

