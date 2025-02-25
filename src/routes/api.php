<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'     => 'v1',
    'middleware' => 'basic.auth.env',
], function () {
    /*--- TradeItemOffer API ---*/
    Route::group(['prefix' => 'product'], function () {
        Route::apiResource('terms', 'TradeItemOfferController', [
            'names'      => [
                'index'   => 'api.product.terms.index',
                'store'   => 'api.product.terms.create',
                'update'  => 'api.product.terms.update',
                'destroy' => 'api.product.terms.destroy',
                'show'    => 'api.product.terms.show',
            ],
            'parameters' => ['terms' => 'id'],
        ]);
    });
    Route::group(['prefix' => 'product'], function () {
        Route::post(
            'terms:import',
            [
                'as'   => 'api.product.terms.store',
                'uses' => 'FileController@store'
            ]
        );
        Route::post(
            'terms:export',
            [
                'as'   => 'api.product.terms.export',
                'uses' => 'FileController@export'
            ]
        );
        Route::post(
            'terms:delete',
            [
                'as'   => 'api.product.terms.delete',
                'uses' => 'FileController@destroy'
            ]
        );
        Route::get(
            'terms:download/{id}',
            [
                'as'   => 'api.product.terms.download',
                'uses' => 'FileController@download'
            ]
        )->where('id', '[0-9]+');
        Route::put(
            'terms:update-import-status/{id}',
            [
                'as'   => 'api.product.terms.update.import.status',
                'uses' => 'TradeItemOfferController@updateImportStatus'
            ]
        )->where('id', '[0-9]+');
    });
});
