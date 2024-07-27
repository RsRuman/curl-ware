<?php

namespace App\Http\Controllers\Api\V1\Admin;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Response as HttpResponse;

#[AllowDynamicProperties]
class CategoryController extends Controller
{
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $categories = $this->categoryRepository->all($request);
        $categories = CategoryResource::collection($categories);

        return Response::json([
            'success' => 'Categories get successfully',
            'data' => $categories,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Show Category
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return Response::json([
                'message' => 'Category not found',
            ], HttpResponse::HTTP_NOT_FOUND);
        }
        $category = new CategoryResource($category);

        return Response::json([
            'message' => 'Category get successfully',
            'data' => $category,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Store category
     * @param CategoryStoreRequest $request
     * @return JsonResponse
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        $data         = $request->safe()->only(['name', 'parent_id']);
        $data['slug'] = Str::slug($data['name']);

        $category = $this->categoryRepository->create($data);

        if (!$category) {
            return Response::json([
                'message' => 'Category not created',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Category created successfully',
        ], HttpResponse::HTTP_CREATED);

    }

    /**
     * Update category
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return Response::json([
                'message' => 'Category not found',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $data         = $request->safe()->only(['name', 'parent_id']);
        $data['slug'] = Str::slug($data['name']);

        $categoryUpdate = $this->categoryRepository->update($id, $data);

        if (!$categoryUpdate) {
            return Response::json([
                'message' => 'Category not updated',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Category updated successfully',
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Delete category
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return Response::json([
                'message' => 'Category not found',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $categoryDelete = $this->categoryRepository->delete($id);

        if (!$categoryDelete) {
            return Response::json([
                'message' => 'Category not deleted',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Category deleted successfully',
        ], HttpResponse::HTTP_OK);
    }
}
