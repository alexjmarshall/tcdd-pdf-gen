<?php

use Illuminate\Http\Request;

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

Route::get('original-titles', 'FuzzySearchController@getOriginalTitles');
Route::get('correct-titles','FuzzySearchController@getCorrectTitles');
Route::post('original-titles', 'FuzzySearchController@storeCorrectTitles');

//uploads
Route::post('store', 'UploadController@store');
Route::post('upload', 'UploadController@upload');

//catalog pdfs
Route::get('pdfview', 'HomeController@pdfview');
Route::get('generate-pdf','HomeController@generatePDF');

//comet list
Route::get('comet-modules', 'CometListController@index');
Route::put('comet-modules', 'CometListController@update');