<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/register",
     * summary="Registration",
     * description="Register New User",
     * operationId="authRegister",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Basic Registration Data",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="name", type="string", format="text", example="Mohidul Islam"),
     *       @OA\Property(property="email", type="string", format="email", example="mohidul@du.ac.bd"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *     response=422,
     *     description="Validation error",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="The given data was invalid."),
     *        @OA\Property(
     *           property="errors",
     *           type="object",
     *           @OA\Property(
     *              property="name",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The name field is required."},
     *              )
     *           ),
     *           @OA\Property(
     *              property="email",
     *              type="array",
     *              collectionFormat="multi",
     *              @OA\Items(
     *                 type="string",
     *                 example={"The email field is required.","The email must be a valid email address."},
     *              )
     *           )
     *        )
     *     )
     *  ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully Registered, Please Login Now")
     *    )
     * )
     *)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $validator->messages(),
            ], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => 'Successfully Registered, Please Login Now']);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Log in",
     * description="Get a JWT token via given credentials",
     * operationId="authLogin",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="mohidul@du.ac.bd"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *    response=401,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Wrong credentials")
     *     )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="object", 
     *          @OA\Property(property="access_token", type="string", example="xxx.yyy.zzz"),
     *          @OA\Property(property="token_type", type="string", example="bearer"),
     *          @OA\Property(property="expires_in", type="number", example="3600"),
     *      ),
     *    )
     * )
     *)
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Wrong credentials'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * @OA\Post(
     * path="/api/logout",
     * summary="Logout",
     * description="Log the user out (Invalidate the token)",
     * operationId="authLogout",
     * tags={"auth"},
     * security={ {"bearer": {} }},
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Successfully logged out")
     *     )
     *     ),
     * @OA\Response(
     *    response=401,
     *    description="error",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Not authorized"),
     *    )
     * )
     * )
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard("api");
    }
}
