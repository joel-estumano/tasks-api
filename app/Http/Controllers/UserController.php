<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="user",
 *     description="user related operations"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="user/register",
     *     summary="register a user",
     *     tags={"user"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={"name", "email" ,"password"},
     *                  @OA\Property(
     *                  property="name",
     *                  description="user name",
     *                  type="string",
     *                  example="Jane Doe"
     *                  ),
     *                  @OA\Property(
     *                  property="email",
     *                  description="user email",
     *                  type="string",
     *                  example="jane@mail.com"
     *                  ),
     *                  @OA\Property(
     *                  property="password",
     *                  description="user password",
     *                  type="string",
     *                  example="asdfzxc8"
     *              ),
     *            ),
     *         ),
     *     ),
     *     @OA\Response(
     *     response=201,
     *     description="successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/UserResponse"),
     *     )
     * )
     */
    public function register(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        $user = User::create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);
        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * @OA\Patch(
     *     path="user/edit",
     *     summary="edit a user",
     *     security={{"bearer_token":{}}},
     *     tags={"user"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                  property="name",
     *                  description="user name",
     *                  type="string",
     *                  example="Jane Doe"
     *                  ),
     *                  @OA\Property(
     *                  property="email",
     *                  description="user email",
     *                  type="string",
     *                  example="jane@mail.com"
     *              ),
     *            ),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/UserResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function edit(Request $req)
    {
        $user = $req->user();
        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id, 
        ]);
        $user->fill($req->all());
        $user->save();
        return response()->json($user, Response::HTTP_OK);
    }

    public function login(Request $req)
    {
        $credentials = $req->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = $req->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            $resp = response()->json([
                'acces_token' => $token,
                'token_type' => 'Bearer'
            ]);
            return response()->json($resp->original, Response::HTTP_OK);
        };
        return response()->json('Unauthorized', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @OA\Get(
     *     path="user/logout",
     *     summary="logout a user",
     *     security={{"bearer_token":{}}},
     *     tags={"user"},
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DefaultResponse"),
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(ref="#/components/schemas/UnauthorizedResponse"),
     *     )
     * )
     */
    public function logout(Request $req)
    {
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'logout successfully'], Response::HTTP_OK);
    }
}
