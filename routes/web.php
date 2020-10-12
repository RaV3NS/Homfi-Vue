<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL as URLAlias;

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
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    URLAlias::forceScheme($_SERVER['HTTP_X_FORWARDED_PROTO']);
}

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin'], function () {
    Auth::routes(['verify' => true]);
});

Route::get('/email/verify', 'VerifyEmailController@execute')->name('verification-email-link');
Route::get('/email/change', 'VerifyEmailController@execute')->name('change-email-link');

Route::group(['middleware' => 'auth', 'prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin'], function () {
    Route::get('/', 'IndexController@index')->name('home');

    Route::get('users/search', 'UsersController@searchAjax')->name('users.search');
    Route::get('users/list', 'UsersController@listAjax')->name('users.list');
    Route::post('users/note/{user}', 'UserNotesController@store')->name('users.note');
    Route::post('users/resend-email/{user}', 'UsersController@resendEmail')->name('users.resend-email');
    Route::resource('users', 'UsersController');

    Route::get('adverts/search', 'AdvertsController@searchAjax')->name('adverts.search');
    Route::get('adverts/list', 'AdvertsController@listAjax')->name('adverts.list');
    Route::put('adverts/update-status/{advert}', 'AdvertsController@updateStatus')->name('adverts.update.status');
    Route::post('adverts/finish-editing/{advert_id}', 'AdvertsController@finishEditing')->name('adverts.update.finish-editing');
    Route::post('adverts/{advert}/add-image', 'AdvertImageController@store')->name('adverts.add-image');
    Route::resource('adverts', 'AdvertsController');

    Route::get('complains/search', 'ComplainsController@searchAjax')->name('complains.search');
    Route::get('complains/list', 'ComplainsController@listAjax')->name('complains.list');
    Route::put('complains/update-status/{complain}', 'ComplainsController@updateStatus')->name('complains.update.status');
    Route::resource('complains', 'ComplainsController');

    Route::get('notifications/list', 'AdminNotificationController@listAjax')->name('notifications.list');
    Route::put('notifications/update-status/{complain}', 'AdminNotificationController@updateStatus')->name('notifications.update.status');
    Route::resource('notifications', 'AdminNotificationController');


    Route::get('administrative/list', 'AdministrativeController@listAjax')->name('administrative.list');
    Route::resource('administrative', 'AdministrativeController')->except(['show']);

    Route::get('content', 'ContentController@index')->name('content.index');
    Route::put('content/{content}', 'ContentController@update')->name('content.update');

    Route::get('cities/search', 'CitiesController@searchAjax')->name('cities.search');
    Route::get('streets/search', 'CitiesController@searchStreetAjax')->name('streets.search');
    Route::get('administrative/search', 'CitiesController@searchAdministrativeAjax')->name('administrative.search');
    Route::get('subway/search', 'CitiesController@searchSubwayAjax')->name('subway.search');

    Route::get('parse', 'ParseController@index');
    Route::get('parse/edit/{id}', 'ParseController@edit');
    Route::post('parse', 'ParseController@preview');
    Route::put('parse', 'ParseController@store');
});

Route::get('/main', 'Front\MapController@main');
Route::get('/profile', 'Front\MapController@profile');
Route::get('/profile/{page}', 'Front\MapController@profile');
Route::get('/advert/create', 'Front\MapController@createAdvert');
Route::get('/advert/edit/{advertId}', 'Front\MapController@editAdvert');
//Route::get('/{city}', 'Front\MapController@index');
Route::get('/{city}-{params}', 'Front\MapController@index');
Route::get('/{city}', 'Front\MapController@index');
Route::get('/{city}/{advertId}', 'Front\MapController@viewAdvert');
Route::get('/adverts/preview/{advertId}', 'Front\MapController@preview');


