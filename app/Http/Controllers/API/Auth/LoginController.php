<?php declare(strict_types=1);

/**
 * Auth Controller
 *
 * @author  Sviatoslav Polyakov <s.polyakov@dinarys.com>
 * @package App\Http\Controllers\API
 */

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\Login;
use App\User;

/**
 * @OA\Schema(
 *      schema="LoginError",
 *      @OA\Property(
 *          property="error",
 *          type="object",
 *          @OA\Property(
 *              property="email",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="password",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          ),
 *          @OA\Property(
 *              property="remember_me",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Logs in User",
     *     operationId="api_login",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     required={"true"}
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     required={"true"}
     *                 ),
     *                 @OA\Property(
     *                     property="remember_me",
     *                     type="boolean",
     *                     example=false,
     *                     required={"false"}
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     ref="#/components/schemas/User"
     *                 ),
     *                 @OA\Property(
     *                     property="access_token",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/LoginError"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Access Denied",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CommonError"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Forbidden",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/CommonError"
     *             )
     *         )
     *     )
     * )
     */

    /**
     * User Login
     *
     * @param Login $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */

    public function execute(Login $request)
    {
        $credentials = $request->only('email', 'password');

        if($request->get('remember_me')) {
            auth('api')->setTTL(config('jwt.refresh_ttl'));
        }

        if (! $token = auth('api')->attempt($credentials))
        {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if(!auth('api')->user()->hasVerifiedEmail()) {
            return response()->json(['error' => 'Unverified'], 403);
        }

        if(auth('api')->user()->status == User::STATUS_DELETED) {
            return response()->json(['error' => 'Deleted'], 403);
        }

        if(auth('api')->user()->status !== User::STATUS_ACTIVE) {
            return response()->json(['error' => 'Blocked'], 403);
        }

        auth('api')->user()->update(['last_login' => now()]);

        return response(['user' => auth('api')->user(), 'access_token' => $token]);
    }

}
