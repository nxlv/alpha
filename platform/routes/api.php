<?php

use Illuminate\Http\Request;
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::get( '/products/all', [ App\Http\Controllers\API\V1\Products::class, 'get_all' ] );
Route::get( '/products/instances/all', [ App\Http\Controllers\API\V1\Products::class, 'get_all_instances' ] );
Route::post( '/products/details', [ App\Http\Controllers\API\V1\Products::class, 'get_product_details' ] );
/**
 * TODO:
 *
 * /products/all/compact
 *
 */

Route::get( '/carriers/all', [ App\Http\Controllers\API\V1\Carriers::class, 'get_all' ] );
/**
 * TODO:
 *
 * /carriers/all/compact
 * /carriers/single/<ID>
 * /carriers/single/<ID>/compact
 * /carriers/single/<ID>/products
 *
 */

Route::get( '/indexes/all', [ App\Http\Controllers\API\V1\Indexes::class, 'get_all' ] );

/**
 * TODO:
 *
 * Income Benefits endpoint
 * Death Benefits endpoint
 * Rules endpoint
 */
Route::post( '/quoting/get/immediate', [ App\Http\Controllers\API\V1\Quoting::class, 'query_spia_dia' ] );

Route::post( '/quoting/get/fixed', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed' ] );
Route::post( '/quoting/get/fixed/guaranteed', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed_guaranteed' ] );
Route::post( '/quoting/get/fixed/chunk/backtested', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed_backtested_return' ] );
Route::post( '/quoting/get/fixed/illustration', [ App\Http\Controllers\API\V1\Quoting::class, 'query_fixed_illustration' ] );
