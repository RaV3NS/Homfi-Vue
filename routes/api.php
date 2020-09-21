<?php declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Api Routes
 *
 * @author  Sviatoslav Poliakov <s.polyakov@dinarys.com>
 * @package routes
 */


/**
 * @OA\Info(
 *     description="Real Estate API",
 *     version="v1.0.0",
 *     title="Restate",
 *     @OA\Contact(
 *         email="s.polyakov@dinarys.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 * @OA\Tag(
 *     name="Auth",
 *     description="Auth API endpoints"
 * )
 * @OA\Tag(
 *     name="User",
 *     description="User API endpoints"
 * )
 * @OA\Server(
 *     description="Restate API Server",
 *     url=L5_SWAGGER_CONST_HOST
 * )
 * @OA\SecurityScheme(
 *     bearerFormat="JWT",
 *     in="header",
 *     type="http",
 *     description="JWT token",
 *     name="JWT",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="JWT"
 * )
 * @OA\Schema(
 *     schema="Unauthorized",
 *     @OA\Property(
 *         property="error",
 *         type="string"
 *     )
 * )
 * @OA\Schema(
 *     schema="CommonError",
 *     @OA\Property(
 *         property="error",
 *         type="string"
 *     )
 * )
 * @OA\Schema(
 *      schema="PageError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="page",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

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
Route::group(['namespace'=>'API\Auth'], function() {
    Route::post('/register', 'RegisterController@execute');
    Route::post('/login', 'LoginController@execute');
    Route::post('/forgot-password', 'ForgotPasswordController@execute');
    Route::post('/reset-password', 'ResetPasswordController@execute');
    Route::post('/verify-email', 'VerifyEmailController@execute');
});

Route::group(['middleware'=>['auth:api'], 'namespace'=>'API'], function(){
    Route::post('/logout', 'Auth\LogoutController@execute');
    Route::post('/change-password', 'Auth\ChangePasswordController@execute');

    Route::get('/user/{user}', 'User\ShowController@execute');
    Route::post('/user/phone/{cypher}', 'User\PhoneListController@execute');
    Route::post('/user/{user}/reset-email', 'User\ResetEmailController@execute');
    Route::put('/user/{user}', 'User\UpdateController@execute');
    Route::delete('/user/{user}', 'User\DeleteController@execute');

    //Route::post('/user/lastAdvert', 'User\Advert\LastAdvertController@execute');

    Route::get('/user/{user}/adverts', 'User\Advert\ListController@execute');
    Route::get('/user/{user}/adverts/{advert}', 'User\Advert\ShowController@execute');
    Route::put('/user/{user}/adverts/{advert}', 'User\Advert\UpdateController@execute');

    Route::post('/user/{user}/adverts/address', 'User\Advert\AddressController@execute');
    Route::post('/user/{user}/adverts/{advert}/contact', 'User\Advert\ContactController@execute');
    Route::post('/user/{user}/adverts/{advert}/parameter', 'User\Advert\ParameterController@execute');
    Route::post('/user/{user}/adverts/{advert}/option', 'User\Advert\OptionController@execute');
    Route::post('/user/{user}/adverts/{advert}/photo', 'User\Advert\PhotoController@execute');
    Route::put('/user/{user}/adverts/{advert}/photo', 'User\Advert\UpdatePhotoController@execute');
    Route::post('/user/{user}/adverts', 'User\Advert\CreateController@execute');
    Route::delete('/user/{user}/adverts/{advert}', 'User\Advert\DeleteAdvertController@execute');

    Route::get('/user/{user}/notifications', 'User\NotificationListController@execute');
    Route::put('/user/{user}/notifications/{notification_id}', 'User\NotificationUpdateController@execute');

    Route::get('/user/{user}/favorites', 'User\Favorite\ListController@execute');
    Route::post('/user/{user}/favorites', 'User\Favorite\CreateController@execute');
    Route::delete('/user/{user}/favorites/{advert}', 'User\Favorite\DeleteController@execute');
    Route::get('/user/{user}/favorites/ids', 'User\Favorite\ListIdController@execute');
});

Route::group(['namespace' => 'API'], function () {
    Route::get('/adverts', 'Advert\ListController@execute');
    Route::get('/adverts-url', 'Advert\UrlListController@execute');
    Route::get('/adverts/coordinates', 'Advert\CoordinatesController@execute');
    Route::post('/adverts/phone/{cypher}', 'Advert\PhoneListController@execute');

    Route::get('/adverts/parameters', 'Advert\ParameterListController@execute');
    Route::get('/adverts/options', 'Advert\OptionListController@execute');

    Route::get('/adverts/{advert}', 'Advert\ShowController@execute');
    Route::get('/adverts/{advert}/view', 'Advert\AddViewController@execute');

    Route::get('/geo/main-cities', 'Geo\MainCitiesListController@execute');
    Route::get('/geo/districts', 'Geo\DistrictController@execute');
    Route::get('/geo/cities', 'Geo\CityController@execute');
    Route::get('/geo/search', 'Geo\SearchController@execute');

    Route::post('/adverts/{advert_id}/complain', 'Complain\CreateController@execute');

    Route::get('/contents', 'Content\ListController@execute');
    Route::get('/contents/{content}', 'Content\ShowController@execute');
});
