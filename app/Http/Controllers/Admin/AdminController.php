<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminFormRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *     title= "Pet Shop API - Swagger Documentation",
 *     version="1.0.0"
 * )
 */
class AdminController extends Controller
{

    /**
     * @param AdminFormRequest $request
     * @param User $user
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/admin/create",
     *     summary="Create an Admin account",
     *     operationId="admin-create",
     *     tags={"Admin"},
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
     *               @OA\Property(property="is_marketing", type="boolean",description="User marketing preferences"),
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
    public function store(AdminFormRequest $request, User $user): JsonResponse
    {
        try {
            $user->fill($request->fields())->save();
            return $this->successResponse(new AdminResource($user), 'admin created successfully', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
