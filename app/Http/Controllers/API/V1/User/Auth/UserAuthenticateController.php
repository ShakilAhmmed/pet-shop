<?php

namespace App\Http\Controllers\API\V1\User\Auth;

use App\Actions\SyncTokenAction;
use App\Enums\AdminStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\Auth\LoginFormRequest;
use App\Models\User;
use App\Services\JWTService\JWTService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserAuthenticateController extends Controller
{
    /**
     * @param LoginFormRequest $request
     * @param SyncTokenAction $tokenAction
     * @return JsonResponse
     * @OA\Post(
     *     path="/api/v1/user/login",
     *     summary="Login an User account",
     *     operationId="user-login",
     *     tags={"User"},
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
     * @throws Throwable
     */
    public function login(LoginFormRequest $request, SyncTokenAction $tokenAction): JsonResponse
    {
        try {
            $user = User::where([
                'email' => $request->input('email'),
                'is_admin' => AdminStatus::NO,
            ])->first();

            if (! ($user && Hash::check($request->input('password'), $user->password))) {
                return $this->errorResponse('Invalid Credentials', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $response = [];
            if ($user->hasValidToken()) {
                $response['token'] = $user->hasValidToken()->token;
                $tokenAction->attach($user, $response['token']);
                return $this->successResponse($response, 'user authenticated successfully');
            }
            $token = JWTService::make()->setPayload($user->uuid)->createToken();
            $response['token'] = $token;
            DB::beginTransaction();
            $user->tokens()->create(['token_title' => 'user login token', 'token' => $token]);
            $tokenAction->attach($user, $token);
            DB::commit();
            return $this->successResponse($response, 'user authenticated successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @return JsonResponse
     * /**
     *
     *
     * @OA\Post(
     *     path="/api/v1/user/logout",
     *     summary="Logout an User account",
     *     operationId="user-logout",
     *     tags={"User"},
     *     security={{"jwt":{}}},
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
    public function logout(SyncTokenAction $tokenAction)
    {
        try {
            $tokenAction->detach(auth()->user());
            return $this->successResponse([], 'user logged out successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
