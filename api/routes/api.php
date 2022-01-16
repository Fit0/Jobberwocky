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

/* Skills */

Route::get('skills', 'Api\SkillController@index');
Route::get('showSkill/{id}', 'Api\SkillController@show');
Route::post('storeSkill', 'Api\SkillController@store');
Route::put('updateSkill', 'Api\SkillController@update');
Route::delete('delSkill/{id}', 'Api\SkillController@destroy');

/* Countries */

Route::get('country', 'Api\CountryController@index');
Route::get('showCountry/{id}', 'Api\CountryController@show');
Route::post('storeCountry', 'Api\CountryController@store');
Route::put('updateCountry', 'Api\CountryController@update');
Route::delete('delCountry/{id}', 'Api\CountryController@destroy');

/* Offers */
Route::get('offers', 'Api\OfferController@index');
Route::get('showOffer/{id}', 'Api\OfferController@showOffer');
Route::get('searchOffer/{name}/{salaryMin}/{salaryMax}/{country_id}', 'Api\OfferController@searchOffer');
Route::post('storeOffer', 'Api\OfferController@store');
Route::put('updateOffer', 'Api\OfferController@update');
Route::delete('delOffer/{id}', 'Api\OfferController@destroy');
