<?php

use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::post('/', 'ProductController@store')->middleware(['auth:admin', 'role:admin']);
        Route::put('admin/{product:id}', 'ProductController@update')->middleware(['auth:admin', 'role:admin']);
        Route::delete('admin/{product:id}', 'ProductController@destroy')->middleware(['auth:admin', 'role:admin']);
    });

    Route::namespace('Shop')->group(function () {
        Route::get('/', 'ProductController@index');
        Route::get('best-selling', 'IndexBestSellingProduct');
        Route::get('specials', 'IndexSpecialProduct');
        Route::get('{product:slug}/similar-products', 'GetSimilarProducts');
        Route::get('items', 'GetProductItems');
        Route::get('{product:slug}', 'ProductController@show');

    });
});

Route::namespace('User')->group(function () {
    Route::get('provinces', 'IndexProvinces');
    Route::get('cities', 'IndexCities');
});


Route::prefix('comments')->group(function () {
    Route::namespace('User')->group(function () {
        Route::post('/', 'CommentController@store')->middleware('throttle:1,60');
    });

    Route::namespace('Admin')->group(function () {
        Route::middleware(['auth:admin', 'role:admin'])->group(function () {
            Route::get('/', 'CommentController@index');
            Route::patch('{comment}/status', 'UpdateCommentStatus');
            Route::delete('{comment}', 'CommentController@destroy');
        });
    });
});

Route::prefix('logs')->group(function () {
    Route::namespace('User')->group(function () {
        Route::post('/', 'LogController@store');
    });
});

Route::prefix('orders')->group(function () {
    Route::namespace('User')->group(function () {
        Route::post('guests', 'GuestOrderController@store');
        Route::get('delivery-cost', 'GetOrderDeliveryCostFormula');
        Route::put('verify', 'VerifyOrder');
        Route::post('notify', 'NotifyUserForOrder');
    });
    Route::get('status', 'User\GetOrderStatus');
});
Route::apiResource('orders', 'User\OrderController')->except(['store', 'destroy'])->middleware('auth:admin');
Route::patch('orders/{order}/reject', 'User\OrderController@reject');

Route::namespace('Blog')->group(function () {
    Route::prefix('article-categories')->group(function () {
        Route::get('/', 'ArticleCategoryController@index');
    });

    Route::prefix('articles')->group(function () {
        Route::get('/', 'ArticleController@index');
        Route::get('/{article:slug}', 'ArticleController@show');
        Route::get('/{article:slug}/related-products', 'GetArticleRelatedProducts');
        Route::get('/{article:slug}/related-articles', 'ArticleRelatedArticleController');
    });
});

Route::prefix('product-categories')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::post('/', 'ProductCategoryController@store')->middleware(['auth:admin', 'role:admin']);
        Route::put('{category}', 'ProductCategoryController@update')->middleware(['auth:admin', 'role:admin']);
        Route::delete('{category}', 'ProductCategoryController@destroy')->middleware(['auth:admin', 'role:admin']);
    });

    Route::namespace('Shop')->group(function () {
        Route::get('/', 'ProductCategoryController@index');
    });
});

Route::prefix('article-categories')->group(function () {
    Route::namespace('Admin')->group(function () {
        Route::post('/', 'ArticleCategoryController@store')->middleware(['auth:admin', 'role:admin']);
        Route::put('{category}', 'ArticleCategoryController@update')->middleware(['auth:admin', 'role:admin']);
        Route::delete('{category}', 'ArticleCategoryController@destroy')->middleware(['auth:admin', 'role:admin']);
    });
});

Route::apiResource('return-requests', 'Shop\ReturnRequestController');

Route::apiResource('banners', 'Shop\BannerController')->except(['index', 'show'])->middleware(['auth:admin', 'role:admin']);
Route::get('banners', 'Shop\BannerController@index');
Route::get('banners/locations', 'Shop\IndexBannerLocation');

Route::namespace('Shop')->group(
    function () {
        Route::get('testimonials', 'GetTestimonials');
    }
);

Route::namespace('Admin')->group(
    function () {
        Route::prefix('admins')->group(
            function () {
                Route::post('auth/login', 'AdminLoginController');
            }
        );

        Route::apiResource('admin/articles', 'ArticleController')
            ->except(['index', 'show'])
            ->middleware(['auth:admin', 'role:writer']);

        Route::apiResource('discount-codes', 'DiscountCodeController')->except('show')->middleware(
            ['auth:admin', 'role:discount_generator']
        );
    }
);

Route::post('verification-codes', 'User\VerificationCodeController@store')->middleware('throttle:1,1');
Route::namespace('User')->group(function () {
    Route::prefix('users')->group(
        function () {
            Route::get('self', 'UserGetSelf')->middleware('auth:user');
            Route::post('/', 'UserController@store');
            Route::post('login', 'LoginUser');
        }
    );
    Route::prefix('carts')->group(
        function () {
            Route::post('products', 'CartItemController@store')
                ->middleware('auth:user');
            Route::get('products', 'CartItemController@index')
                ->middleware('auth:user');
            Route::patch('products/{cartProduct}', 'CartItemController@update')
                ->middleware('auth:user');
            Route::delete('products/{cartProduct}', 'CartItemController@destroy')
                ->middleware('auth:user');
        }
    );

    Route::prefix('user-details')->group(
        function () {
            Route::put('/', 'UserDetailController@update')
                ->middleware('auth:user');
            Route::put('passwords', 'UpdateUserPassword')->middleware('auth:user');
        }
    );

    Route::apiResource('addresses', 'AddressController')
        ->middleware('auth:user');

    Route::prefix('transactions')->group(
        function () {
            Route::post('/', 'TransactionController@store');
            Route::get('verify', 'TransactionController@verify')->name('transactions.verify');
        }
    );

    Route::get('discount-codes/evaluate', 'EvaluateDiscountCode');

    Route::post('messages', 'MessageController@store');
}
);

Route::get('admins/self', 'Admin\AdminController@getSelf')->middleware('auth:admin');
