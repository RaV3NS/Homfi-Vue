<?php declare(strict_types=1);

/**
 * Register Controller
 *
 * @author  Sviatoslav Polyakov <s.polyakov@dinarys.com>
 * @package App\Http\Controllers\API
 */

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\Register;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *      schema="RegisterUserError",
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
 *              property="lang",
 *              type="array",
 *              description="It contains an array of errors, if any, for this field",
 *              @OA\Items(type="string")
 *          )
 *      )
 *  )
 */

class RegisterController extends Controller
{

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Register User",
     *     operationId="register_user",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="email",
     *                    type="string",
     *                    format="email",
     *                    required={"true"},
     *                ),
     *                @OA\Property(
     *                    property="password",
     *                    type="string",
     *                    required={"true"},
     *                ),
     *                @OA\Property(
     *                    property="lang",
     *                    type="string",
     *                    required={"false"},
     *                    example="uk"
     *                )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                type="object",
     *                @OA\Property(
     *                    property="access_token",
     *                    type="string",
     *                ),
     *                @OA\Property(
     *                    property="expires_in",
     *                    type="integer",
     *                )
     *             )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/RegisterUserError"
     *             )
     *         )
     *     )
     * )
     */

     /**
     * @param Register $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(Register $request)
    {
        $validated = $request->validated();

//        $validated['first_name'] = '';
//        $validated['last_name'] = '';

        if($request->has('lang')) {
            app()->setLocale($validated['lang']);
        }

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        event(new Registered($user));

        //$token = auth('api')->login($user);
        //$this->respondWithToken($token);

        return response()->json([]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }

}
