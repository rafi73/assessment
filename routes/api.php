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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function ($router) {
    Route::get('test', 'App\Http\Controllers\SyncController@index');
    // Route::get('chapter-headings/{id}', 'App\Http\Controllers\ChapterHeadingController@show');
    // Route::post('chapter-headings', 'App\Http\Controllers\ChapterHeadingController@create');
    // Route::put('chapter-headings/{id}', 'App\Http\Controllers\ChapterHeadingController@update');
    // Route::delete('chapter-headings/{id}', 'App\Http\Controllers\ChapterHeadingController@delete');
    Route::post('update-product', 'App\Http\Controllers\SyncController@productUpdate');
    Route::get('products', 'App\Http\Controllers\SyncController@products');
    // Route::get('chapter-headings-front', 'App\Http\Controllers\ChapterHeadingController@getChapterHeadings');

    // Route::get('code-categories', 'App\Http\Controllers\CodeCategoryController@getCodeCategories');
    // Route::get('suppliers', 'App\Http\Controllers\SupplierController@getSuppliers');
    // Route::get('countries', 'App\Http\Controllers\CountryController@getCountries');
    // Route::get('exports/{chapterHeadingId}', 'App\Http\Controllers\ExportController@getExportData');
    // Route::get('exports-by-country/{chapterHeadingId}/{countryId}', 'App\Http\Controllers\ExportController@getExportDataByCountry');
});