<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\Auth\CustomLoginController@index')->name('login');
Route::post('/custom/login', 'App\Http\Controllers\Auth\CustomLoginController@login')->name('custom/login');
Route::post('/custom/logout', 'App\Http\Controllers\Auth\CustomLoginController@logout')->name('custom/logout');

Route::group(['middleware' => ['auth', 'isLogin']], function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');
    Route::group(['prefix' => 'test-jp', 'namespace' => 'App\Http\Controllers'], function () {
        Route::get('/transaction', 'TransactionController@index')->name('test-jp/transaction');
        Route::get('/transaction-add', 'TransactionController@create')->name('test-jp/transaction-add');
        Route::post('/transaction-data', 'TransactionController@transactionData')->name('test-jp/transaction-data');
        Route::post('/transaction-store', 'TransactionController@store')->name('test-jp/transaction-store');
        Route::delete('/transaction-delete', 'TransactionController@destroy')->name('test-jp/transaction-delete');
        Route::put('/transaction-update/{transaction:code}', 'TransactionController@update')->name('test-jp/transaction-update');

        // product 
        Route::get('/product-show/{id}', 'TransactionController@productShow')->name('test-jp/product-show');
        Route::get('/product-options', 'TransactionController@productOptions')->name('test-jp/product-options');
        Route::post('/product-data', 'TransactionController@productData')->name('test-jp/product-data');
        Route::post('/product-add', 'TransactionController@productAdd')->name('test-jp/product-add');
        Route::put('/product-update/{product:id}', 'TransactionController@productUpdate')->name('test-jp/product-update');
        Route::delete('/product-delete', 'TransactionController@productDestroy')->name('test-jp/product-delete');

        // customer 
        Route::get('/customer-show/{customer:id}', 'TransactionController@customerShow')->name('test-jp/customer-show');
        Route::get('/customer-options', 'TransactionController@customerOptions')->name('test-jp/customer-options');
        Route::post('/customer-data', 'TransactionController@customerData')->name('test-jp/customer-data');
        Route::post('/customer-add', 'TransactionController@customerAdd')->name('test-jp/customer-add');
        Route::put('/customer-update/{customer:id}', 'TransactionController@customerUpdate')->name('test-jp/customer-update');
        Route::delete('/customer-delete', 'TransactionController@customerDestroy')->name('test-jp/customer-delete');
    });
});
