<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Filters\DefineFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AdminFormRequest;
use App\Http\Requests\V1\User\UserFormRequest;
use App\Http\Resources\V1\Admin\AdminResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use App\Services\JWTService\JWTService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
     * @throws Throwable
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
    public function store(AdminFormRequest $request, User $user): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user->fill($request->fields())->save();
            $token = JWTService::make()->setPayload($user->uuid)->createToken();
            $user->tokens()->create(['token_title' => 'login token', 'token' => $token]);
            DB::commit();
            return $this->successResponse(new AdminResource($user), 'admin created successfully', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/user-listing",
     *     summary="List all users",
     *     operationId="admin-user-listing",
     *     tags={"Admin"},
     *     security={{"jwt":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="integer",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="integer",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="sortBy",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="desc",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="boolean",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="first_name",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *      @OA\Parameter(
     *         name="email",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="phone",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="address",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="created_at",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *      ),
     *     @OA\Parameter(
     *         name="marketing",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *           type="string",
     *           enum={"0", "1"}
     *        ),
     *      ),
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

    public function userList(Request $request)
    {
        try {
            $limit = $request->query('limit', 15);
            $sortBy = $request->query('sortBy');
            $isDesc = $request->query('desc', false);

            $users = User::query()
                ->user()
                ->sort($sortBy, $isDesc)
                ->when($request->query('first_name'), DefineFilter::with('first_name', $request->query('first_name')))
                ->when($request->query('email'), DefineFilter::with('email', $request->query('email')))
                ->when($request->query('phone'), DefineFilter::with('email', $request->query('phone')))
                ->when($request->query('address'), DefineFilter::with('address', $request->query('address')))
                ->when($request->query('created_at'), DefineFilter::with('created_at', $request->query('created_at')))
                ->when($request->query('marketing'), DefineFilter::with('is_marketing', $request->query('marketing')))
                ->paginate($limit);
            return $this->successResponse(UserResource::collection($users), 'users fetched successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param UserFormRequest $request
     * @param $uuid
     * @return JsonResponse
     *
     * @throws Throwable
     * @OA\Put(
     *     path="/api/v1/admin/user-edit/{uuid}",
     *     summary="Edit a User account",
     *     operationId="admin-user-edit",
     *     tags={"Admin"},
     *     security={{"jwt":{}}},
     *   @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *  ),
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

    public function userEdit(UserFormRequest $request, $uuid): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = User::where('uuid', $uuid)->first();
            $user->fill($request->fields())->save();
            DB::commit();
            return $this->successResponse(new AdminResource($user), 'user updated successfully', Response::HTTP_CREATED);
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }


    /**
     * @param $uuid
     * @return JsonResponse
     *
     * @throws Throwable
     * @OA\Delete(
     *     path="/api/v1/admin/user-delete/{uuid}",
     *     summary="Delete a User account",
     *     operationId="admin-user-delete",
     *     tags={"Admin"},
     *     security={{"jwt":{}}},
     *   @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *        ),
     *  ),
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
    public function userDelete($uuid): JsonResponse
    {
        try {
            DB::beginTransaction();
            User::where('uuid', $uuid)->delete();
            DB::commit();
            return $this->successResponse([], 'user deleted successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
