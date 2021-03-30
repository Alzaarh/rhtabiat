<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryHierarchyController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/banners/main', [BannerController::class, 'getMain']);
Route::apiResource('/banners', BannerController::class);

Route::apiResource('/posters', PosterController::class)->except([
    'show', 'update',
]);

Route::get('/categories/hierarchy', CategoryHierarchyController::class);
Route::apiResource('/categories', CategoryController::class);

Route::get('/products/search', ProductSearchController::class);
Route::apiResource('/products', ProductController::class);
Route::get(
    '/products/{product}/related', [ProductController::class, 'getRelated']
);
Route::get(
    '/products/{product}/comments', [ProductController::class, 'getComments']
);
Route::post(
    '/products/{product}/comments', [ProductController::class, 'addComment']
);
/* Admin related routes */
Route::get('/admins/comments', [AdminController::class, 'getComments'])->middleware([
    'auth:admin',
    'role:boss',
]);
Route::get('/admins/comments/{comment}', [AdminController::class, 'getComment'])->middleware([
    'auth:admin',
    'role:boss',
]);
Route::delete('/admins/comments/{comment}', [AdminController::class, 'deleteComment'])->middleware([
    'auth:admin',
    'role:boss',
]);
Route::patch('/admins/comments/{comment}/verify', [AdminController::class, 'verifyComment'])->middleware([
    'auth:admin',
    'role:boss',
]);

Route::get('/admins/roles', [AdminController::class, 'roles'])->middleware(['auth:admin', 'role:boss']);

Route::post('/admins/login', [AdminController::class, 'login']);
Route::apiResource('/admins', AdminController::class)->middleware(['auth:admin', 'role:boss']);

/* User related routes */
Route::post('/users/register', [UserController::class, 'register']);
Route::post(
    '/users/register/verify', [UserController::class, 'verifyRegister']
)->name('register.verify');
Route::post('/users/login/verify', [UserController::class, 'verifyLogin'])
    ->name('login.verify');

Route::post('/users/verify-phone', [UserController::class, 'verifyPhone']);
Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/verify-login', [UserController::class, 'verifyLogin']);

Route::get('/comments', [CommentController::class, 'index']);

Route::apiResource('/contacts', ContactController::class)->except('update');

Route::apiResource('/blogs/categories', BlogCategoryController::class);

Route::post('/blogs/articles/{article}/comments', [ArticleController::class, 'addComment']);

Route::apiResource('/articles', ArticleController::class);

Route::apiResource('/orders', OrderController::class);

// User auth
Route::post('/users/login', UserLoginController::class);

// User dashboard
Route::get('/users/self', [UserController::class, 'getSelf']);
Route::apiResource('/users/addresses', AddressController::class);
Route::apiResource('/users/orders', OrderController::class);
