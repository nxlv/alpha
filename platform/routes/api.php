<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Product endpoints
|--------------------------------------------------------------------------
*/
Route::get( '/products/all', [ App\Http\Controllers\API\V1\Products::class, 'get_all' ] );
Route::get( '/products/instances/all', [ App\Http\Controllers\API\V1\Products::class, 'get_all_instances' ] );
Route::post( '/products/details', [ App\Http\Controllers\API\V1\Products::class, 'get_product_details' ] );

/*
|--------------------------------------------------------------------------
| Carriers/Indices endpoints
|--------------------------------------------------------------------------
*/
Route::get( '/carriers/all', [ App\Http\Controllers\API\V1\Carriers::class, 'get_all' ] );
Route::get( '/indexes/all', [ App\Http\Controllers\API\V1\Indexes::class, 'get_all' ] );
Route::get( '/indexes/reports/all', [ App\Http\Controllers\API\V2\Indexes::class, 'get_reports' ] );
Route::get( '/indexes/reports/annuities/all', [ App\Http\Controllers\API\V2\Indexes::class, 'get_annuities' ] );
Route::get( '/notices/all', [ App\Http\Controllers\API\V1\Products::class, 'get_all_notices' ] );

/*
|--------------------------------------------------------------------------
| Quoting endpoints
|--------------------------------------------------------------------------
*/
Route::post( '/quoting/get/immediate', [ App\Http\Controllers\API\V1\Quoting::class, 'query_spia_dia' ] );

Route::post( '/quoting/get/fixed', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed' ] );
Route::post( '/quoting/get/fixed/guaranteed', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed_guaranteed' ] );
Route::post( '/quoting/get/fixed/chunk/backtested', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed_backtested_return' ] );
Route::post( '/quoting/get/fixed/illustration', [ App\Http\Controllers\API\V2\Quoting::class, 'query_fixed_illustration' ] );

Route::post( '/quoting/report', [ App\Http\Controllers\API\V2\Illustrating::class, 'fetch_report' ] );

/**
 * TODO:
 *
 * Income Benefits endpoint
 * Death Benefits endpoint
 * Rules endpoint
 */
