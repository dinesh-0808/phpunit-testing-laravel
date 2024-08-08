<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

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

// Route::middleware('auth')->get('/products', function () {
//     $products = Product::paginate(10);
//     return view('products',compact('products'));
// });

Auth::routes();


Route::redirect('/', 'login');

Route::middleware('auth')->group(function(){
    Route::get('/products',[ProductController::class, 'index'])->name('products.index');

    Route::middleware('is_admin')->group(function(){
        Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/products',[ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}',[ProductController::class, 'edit'])->name('products.edit');
        Route::patch('/products/{id}',[ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}',[ProductController::class, 'destroy'])->name('products.destroy');
    });
});
Route::get('/product/api',function(){
    return Product::all();
})->name('product.api');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
