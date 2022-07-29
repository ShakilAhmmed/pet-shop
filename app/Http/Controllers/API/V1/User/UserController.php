<?php

namespace App\Http\Controllers\API\V1\User;

use App\Actions\SyncTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminFormRequest;
use App\Http\Requests\User\UserFormRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\User;
use App\Services\JWTService\JWTService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UserController extends Controller
{
    /**
     * @param UserFormRequest $request
     * @param User $user
     * @return JsonResponse
     *
     * @throws Throwable
     * @OA\Post(
     *     path="/api/v1/user/create",
     *     summary="Create a User account",
     *     operationId="user-create",
     *     tags={"User"},
     * @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"first_name","last_name", "email","password", "password_confirmation","address","phone_number"},
     *               @OA\Property(property="first_name", type="text",description="User firstname"),
     *               @OA\Property(property="last_name", type="text",description="User lastname"),
     *               @OA\Property(property="email", type="text",description="User email"),
     *               @OA\Property(property="password", type="password",description="User password"),
     *               @OA\Property(property="password_confirmation", type="password",description="User password"),
     *               @OA\Property(property="address", type="text",description="User main address"),
     *               @OA\Property(property="phone_number", type="text",description="User main phone number"),
     *               @OA\Property(property="is_marketing", type="text",description="User marketing preferences"),
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
    public function store(UserFormRequest $request, User $user): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user->fill($request->fields())->save();
            $token = JWTService::make()->setPayload($user->uuid)->createToken();
            $user->tokens()->create(['token_title' => 'user login token', 'token' => $token]);
            DB::commit();
            return $this->successResponse(new AdminResource($user), 'user created successfully', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
