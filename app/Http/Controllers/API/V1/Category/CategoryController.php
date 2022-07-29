<?php

namespace App\Http\Controllers\API\V1\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminFormRequest;
use App\Http\Requests\Category\CategoryFormRequest;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     summary="List all categories",
     *     operationId="categories-listing",
     *     tags={"Categories"},
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

    public function index(Request $request)
    {
        try {
            $limit = $request->query('limit', 15);
            $sortBy = $request->query('sortBy');
            $isDesc = $request->query('desc', false);

            $categories = Category::query()
                ->sort($sortBy, $isDesc)
                ->paginate($limit);

            return $this->successResponse(
                CategoryResource::collection($categories),
                'categories fetched successfully',
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param CategoryFormRequest $request
     * @param Category $category
     * @return JsonResponse
     *
     * @OA\Post(
     *     path="/api/v1/category/create",
     *     summary="Create a new category",
     *     operationId="categories-create",
     *     tags={"Categories"},
     *     security={{"jwt":{}}},
     * @OA\RequestBody(
     *          required=true,
     *         @OA\MediaType(
     *            mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *               type="object",
     *               required={"title"},
     *               @OA\Property(property="title", type="text",description="Category title"),
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

    public function store(CategoryFormRequest $request, Category $category): JsonResponse
    {
        try {
            $category->fill($request->fields())->save();
            return $this->successResponse(
                new CategoryResource($category),
                'category created successfully',
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param CategoryFormRequest $request
     * @param Category $category
     * @return JsonResponse
     *
     * @OA\Put(
     *     path="/api/v1/category/{uuid}",
     *     summary="Update an existing category",
     *     operationId="categories-update",
     *     tags={"Categories"},
     *     security={{"jwt":{}}},
     * @OA\Parameter(
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
     *               required={"title"},
     *               @OA\Property(property="title", type="text",description="Category title"),
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

    public function update(CategoryFormRequest $request, Category $category): JsonResponse
    {
        try {
            $category->fill($request->fields())->save();
            return $this->successResponse(
                new CategoryResource($category),
                'category updated successfully',
                Response::HTTP_CREATED
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param Category $category
     * @return JsonResponse
     *
     * @OA\Get(
     *     path="/api/v1/category/{uuid}",
     *     summary="Fetch a category",
     *     operationId="categories-read",
     *     tags={"Categories"},
     *     security={{"jwt":{}}},
     * @OA\Parameter(
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

    public function show(Category $category): JsonResponse
    {
        try {
            return $this->successResponse(
                new CategoryResource($category),
                'category fetched successfully',
            );
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }

    /**
     * @param Category $category
     * @return JsonResponse
     *
     * @OA\Delete(
     *     path="/api/v1/category/{uuid}",
     *     summary="Delete an existing category",
     *     operationId="categories-delete",
     *     tags={"Categories"},
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

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return $this->successResponse([], 'category deleted successfully');
        } catch (Exception $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
