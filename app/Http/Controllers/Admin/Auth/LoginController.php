<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginFormRequest;
use App\Models\User;
use App\Services\JWTService\JWTService;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @param LoginFormRequest $request
     * @return JsonResponse
     * /**
     *
     *
     * @OA\Post(
     *     path="/api/v1/admin/login",
     *     summary="Login an Admin account",
     *     operationId="admin-login",
     *     tags={"Admin"},
     * @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"email","password"},
     *               @OA\Property(property="email", type="text",description="User email"),
     *               @OA\Property(property="password", type="password",description="User password"),
     *            ),
     *        ),
     *    ),
     *     @OA\Response(
     *        response=200,
     *        description="OK",
     *     ),
     *     @OA\Response(
     *        response=401,
     *        description="Unauthorized",
     *     ),
     *     @OA\Response(
     *        response=404,
     *        description="Page not found",
     *     ),
     *     @OA\Response(
     *        response=500,
     *        description="Internal server error",
     *     ),
     *     @OA\Response(
     *        response=422,
     *        description="Validation Failed",
     *     ),
     * ),
     */
    public function login(LoginFormRequest $request): JsonResponse
    {
        try {
            $user = User::query()->where('email', $request->input('email'))->first();
            if (!($user && Hash::check($request->input('password'), $user->password))) {
                return $this->errorResponse('Invalid Credentials', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $token = JWTService::for($user->uuid)->createToken();
            $response = [
                "token" => $token,
            ];
            return $this->successResponse($response, 'authenticated successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
