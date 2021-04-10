<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleSearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BestSellingProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryHierarchyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosterController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\ProductController;
use App\Http\Controllers\Shop\ProductCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/banners/main', 'BannerController@getMain');
Route::apiResource('/banners', 'BannerController');

// Route::get('/categories/hierarchy', CategoryHierarchyController::class);
// Route::apiResource('/categories', CategoryController::class);

// Route::get(
//     '/products/{product}/related', [ProductController::class, 'getRelated']
// );
// Route::get(
//     '/products/{product}/comments', [ProductController::class, 'getComments']
// );
// Route::post(
//     '/products/{product}/comments', [ProductController::class, 'addComment']
// );
/* Admin related routes */

// Route::get('/admins/comments', [AdminController::class, 'getComments'])->middleware([
//     'auth:admin',
//     'role:boss',
// ]);
// Route::get('/admins/comments/{comment}', [AdminController::class, 'getComment'])->middleware([
//     'auth:admin',
//     'role:boss',
// ]);
// Route::delete('/admins/comments/{comment}', [AdminController::class, 'deleteComment'])->middleware([
//     'auth:admin',
//     'role:boss',
// ]);
// Route::patch('/admins/comments/{comment}/verify', [AdminController::class, 'verifyComment'])->middleware([
//     'auth:admin',
//     'role:boss',
// ]);

Route::get('/admins/roles', 'AdminController@roles');

// Route::post('/admins/login', [AdminController::class, 'login']);
Route::apiResource('/admins', 'AdminController');

// /* User related routes */
Route::post('/users/register', 'UserController@register');
Route::post(
    '/users/register/verify',
    'UserController@verifyRegister'
)->name('register.verify');
Route::post('/users/login/verify', 'UserController@verifyLogin')
    ->name('login.verify');

Route::post('/users/verify-phone', [UserController::class, 'verifyPhone']);
Route::post('/users/login', 'UserController@login');
Route::post('/users/verify-login', [UserController::class, 'verifyLogin']);

Route::get('/admins/comments', 'CommentController@index');
Route::patch('/admins/comments/{comment}', 'CommentController@update');

// Route::post('/blogs/articles/{article}/comments', [ArticleController::class, 'addComment']);

// Route::apiResource('/orders', OrderController::class);
// // Product
// Route::get('/products/best-selling', BestSellingProductController::class);
// // Route::apiResource('/products', ProductController::class);

// // User auth
// Route::prefix('/auth')->group(function () {
//     Route::post('login', [AuthController::class, 'login']);
//     Route::post('forget-password', [AuthController::class, 'forgetPassword']);
// });

// // User dashboard
Route::get('/users/self', 'UserController@getSelf');
Route::put('/users/self', 'UserController@updateSelf');
Route::apiResource('/users/addresses', 'AddressController');
// Route::apiResource('/users/orders', OrderController::class);

// // Cart
// Route::apiResource('/users/carts', CartController::class);

// // Poster
// Route::apiResource('/posters', PosterController::class);

// // Article
// Route::apiResource('/article-categories', ArticleCategoryController::class);
// Route::get('/articles/search', ArticleSearchController::class);
// Route::apiResource('/articles', 'ArticleController');

// // Message (Contact us)
Route::apiResource('/messages', 'MessageController')->except('update');

Route::namespace('Shop')->group(function () {
    Route::prefix('product-categories')->group(function () {
        Route::get('/', 'ProductCategoryController@index');
        Route::get('/{productCategory:slug}', 'ProductCategoryController@show');
    });
    Route::prefix('products')->group(function () {
        Route::get('/', 'ProductController@index');
        Route::get('{product:slug}', 'ProductController@show')->name('products.show');
    });
});

Route::namespace('Admin')->group(function () {
    Route::prefix('admins')->group(function () {
        Route::post('auth/login', 'AdminLoginController');
    });
    Route::apiResource('product-categories', 'ProductCategoryController')
        ->except(['index', 'show'])
        ->middleware(['auth:admin', 'role:admin']);
    Route::apiResource('products', 'ProductController')
        ->except(['index', 'show'])
        ->middleware(['auth:admin', 'role:admin']);
    Route::apiResource('article-categories', 'ArticleCategoryController')
        ->except(['index', 'show'])
        ->middleware(['auth:admin', 'role:admin']);
    Route::apiResource('comments', 'CommentController')
        ->except(['store', 'show'])
        ->middleware(['auth:admin', 'role:admin']);
    Route::apiResource('articles', 'ArticleController')
        ->except(['index', 'show'])
        ->middleware(['auth:admin', 'role:writer']);
});

Route::namespace('Blog')->group(function () {
    Route::prefix('article-categories')->group(function () {
        Route::get('/', 'ArticleCategoryController@index');
    });
    Route::prefix('articles')->group(function () {
        Route::get('/', 'ArticleController@index');
        Route::get('/{article:slug}', 'ArticleController@show')->name('articles.show');
        Route::get('/{article:slug}/related_products', 'ArticleRelatedProductController');
        Route::get('/{article:slug}/related_articles', 'ArticleRelatedArticleController');
    });
});

Route::namespace('User')->group(function () {
    Route::get('provinces', 'ProvinceIndexController');
    Route::get('cities', 'CityIndexController');
    Route::prefix('comments')->group(function () {
        Route::post('/', 'CommentController@store');
    });
    Route::prefix('orders')->group(function () {
        Route::post('/', 'OrderController@store');
        Route::get('delivery-costs', 'OrderCalculateDeliveryCostController');
    });
    Route::prefix('verification-codes')->group(function () {
        Route::post('/', 'VerificationCodeController@store');
    });
    Route::prefix('users')->group(function () {
        Route::post('/', 'UserController@store');
    });
    Route::prefix('carts')->group(function () {
        Route::post('products', 'CartItemController@store')
            ->middleware('auth:user');
        Route::get('products', 'CartItemController@index')
            ->middleware('auth:user');
        Route::patch('products/{cartProduct}', 'CartItemController@update')
            ->middleware('auth:user');
        Route::delete('products/{cartProduct}', 'CartItemController@destroy')
            ->middleware('auth:user');
    });
});
