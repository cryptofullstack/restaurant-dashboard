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
    return redirect()->route('admin.dashboard');
})->name('globalUrl');

Route::prefix('admin')->group(function () {
    Route::get('/', 'Admin\AdminController@index')->name('admin.dashboard');

    Route::get('login', 'Admin\Auth\AdminLoginController@showAdminLoginForm')->name('admin.login');
    Route::post('login', 'Admin\Auth\AdminLoginController@login');
    Route::post('logout', 'Admin\Auth\AdminLoginController@logout')->name('admin.logout');

    Route::prefix('users')->group(function () {
        Route::get('/', 'Admin\AdminController@users')->name('admin.users');
        Route::get('/getUserDatas', 'Admin\AdminController@getAllUsers');
        Route::get('/destroy/{userId}', 'Admin\AdminController@deleteUser');
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', 'Admin\SettingController@index')->name('admin.settings');
        Route::post('/update/basic', 'Admin\SettingController@updateBasic')->name('admin.settings.update.basic');
        Route::post('/update/location', 'Admin\SettingController@updateLocation')->name('admin.settings.update.location');
        Route::post('/update/time', 'Admin\SettingController@updateTime')->name('admin.settings.update.time');
        Route::post('/update/mainImg', 'Admin\SettingController@updateMainImg')->name('admin.settings.update.mainimg');
        Route::post('/update/description', 'Admin\SettingController@updateDescription')->name('admin.settings.update.description');
        Route::post('/upload/mainPhoto', 'Admin\SettingController@uploadMainPhoto')->name('admin.settings.upload.main');
    });

    Route::prefix('cupons')->group(function () {
        Route::get('/', 'Admin\CuponController@index')->name('admin.cupons');
        Route::get('/getCuponDatas', 'Admin\CuponController@getAllCupons');
        Route::get('/getCuponeUsers', 'Admin\CuponController@getAllCuponUsers');
        Route::post('/insert', 'Admin\CuponController@storeCupon')->name('admin.cupons.insert');
        Route::get('/destroy/{cuponId}', 'Admin\CuponController@deleteCupon');
    });

    Route::prefix('category')->group(function () {
        Route::get('/', 'Admin\CategoryController@index')->name('admin.categories');
        Route::get('/getAllDatas', 'Admin\CategoryController@getAllCategories');
        Route::post('/insert', 'Admin\CategoryController@insertCategory')->name('admin.categories.insert');
        Route::post('/update', 'Admin\CategoryController@updateCategory')->name('admin.categories.update');
        Route::get('/getCategoryData/{id}', 'Admin\CategoryController@getSingleCategory');
        Route::get('/destroy/{id}', 'Admin\CategoryController@deleteCategory');
        Route::post('/setOrder', 'Admin\CategoryController@setCategoryOrder');
        Route::get('/product/{categoryId}', 'Admin\CategoryController@viewSingleCategoryDetail')->name('admin.categories.products');
        Route::get('/products/getAllDatas/{categoryId}', 'Admin\CategoryController@getAllCategoryProducts');
        Route::get('/unProducts/getAllDatas/{categoryId}', 'Admin\CategoryController@getAllCategoryUnProducts');
        Route::post('/products/insert', 'Admin\CategoryController@insertCategoryProducts')->name('admin.categories.products.insert');
        Route::get('/product/destroy/{catProId}', 'Admin\CategoryController@removeCatProduct');
        Route::post('/products/setOrder', 'Admin\CategoryController@setCategoryProductsOrder');
    });

    Route::prefix('product')->group(function () {
        Route::get('/', 'Admin\ProductController@index')->name('admin.products');
        Route::get('/getAllDatas', 'Admin\ProductController@getAllProducts');
        Route::post('/insert', 'Admin\ProductController@insertProduct')->name('admin.products.insert');
        Route::get('/getProductData/{id}', 'Admin\ProductController@getSingleProduct');
        Route::post('/update', 'Admin\ProductController@updateProduct')->name('admin.products.update');
        Route::get('/destroy/{id}', 'Admin\ProductController@deleteProduct');
        Route::get('/groups/{productId}', 'Admin\ProductController@viewSingleProductDetail')->name('admin.products.groups');
        Route::post('/groupExtra/insert', 'Admin\ProductController@insertProductGroupExtra')->name('admin.products.groupExtra.insert');
        Route::get('/group/getAllData/{productId}', 'Admin\ProductController@getProductGroups');
        Route::get('/group/getSingleGroup/{groupId}', 'Admin\ProductController@getSingleGroup');
        Route::get('/group/destroy/{groupId}', 'Admin\ProductController@deleteProductGroup');
        Route::get('/group/getSingleProductGroup/{groupId}', 'Admin\ProductController@getSingleProductGroup');
        Route::post('/groupExtra/update', 'Admin\ProductController@updateProductGroupExtra')->name('admin.products.groupExtra.update');
        Route::post('/group/setOrder/{productId}', 'Admin\ProductController@setProductGroupOrder');
        Route::post('/group/single/setOrder', 'Admin\ProductController@setProductSingleGroupOrder');
    });

    Route::prefix('extra')->group(function () {
        Route::get('/', 'Admin\ExtraController@index')->name('admin.extras');
        Route::get('/getAllDatas', 'Admin\ExtraController@getAllExtras');
        Route::post('/insert', 'Admin\ExtraController@insertExtra')->name('admin.extras.insert');
        Route::get('/getExtraData/{id}', 'Admin\ExtraController@getSingleExtra');
        Route::post('/update', 'Admin\ExtraController@updateExtra')->name('admin.extras.update');
        Route::get('/destroy/{id}', 'Admin\ExtraController@deleteExtra');
    });

    Route::prefix('push')->group(function () {
        Route::get('/', 'Admin\NotifyController@viewPush')->name('admin.push');
        Route::get('/getAllData', 'Admin\NotifyController@getAllData');
        Route::post('/notify', 'Admin\NotifyController@sendNotify')->name('admin.push.notify.send');
    });

    Route::prefix('order')->group(function () {
        Route::get('/', 'Admin\OrderController@viewOrders')->name('admin.orders');
        Route::get('/getAllData', 'Admin\OrderController@getAllData');
        Route::get('/detail/{orderId}', 'Admin\OrderController@viewSingleOrder')->name('admin.orders.single');
    });
});


Route::get('/complete/mollie', function() {
    return view('payment');
})->name('mollietest');

Route::post('/pay/status', 'Admin\AdminController@paymentStatus')->name('payment.webhook');
