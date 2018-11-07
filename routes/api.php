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

// create a user
Route::post('user', 'UserController@store');

// create or update a subscription for a user
Route::post('subscription', 'SubscriptionController@store');

// delete a subscription for a user
Route::post('subscription/delete', 'SubscriptionController@destroy');

// create push notification
Route::post('notify', 'NotificationController@notify');

