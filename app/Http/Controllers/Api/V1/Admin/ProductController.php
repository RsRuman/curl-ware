<?php

namespace App\Http\Controllers\Api\V1\Admin;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Response as HttpResponse;

#[AllowDynamicProperties]
class ProductController extends Controller
{
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * List product
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $products = $this->productRepository->all($request);
        $products = ProductResource::collection($products);
        $products = $this->collectionResponse($products);

        return Response::json([
            'message' => 'Products retrieved successfully',
            'data' => $products,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Show product
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return Response::json([
                'message' => 'Product not found',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $product = new ProductResource($product);

        return Response::json([
            'message' => 'Product retrieved successfully',
            'data' => $product,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Store product
     * @param ProductStoreRequest $request
     * @return JsonResponse
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        $data         = $request->safe()->only(['name', 'description', 'price', 'status']);
        $data['slug'] = Str::slug($data['name']);

        $product = $this->productRepository->create($data);

        if (!$product) {
            return Response::json([
                'message' => 'Product could not be created',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Attach categories
        $product->categories()->attach($request->input('categories'));

        return Response::json([
            'message' => 'Product has been created',
        ], HttpResponse::HTTP_CREATED);
    }

    /**
     * Update product
     * @param ProductStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProductStoreRequest $request, int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return Response::json([
                'message' => 'Product could not be updated',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $data         = $request->safe()->only(['name', 'description', 'price', 'status']);
        $data['slug'] = Str::slug($data['name']);

        $productUpdate = $this->productRepository->update($data, $id);

        if (!$productUpdate) {
            return Response::json([
                'message' => 'Product could not be updated',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Update categories
        $product->categories()->sync($request->input('categories'));

        return Response::json([
            'message' => 'Product has been updated',
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Delete product
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return Response::json([
                'message' => 'Product could not be deleted',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $productDelete = $this->productRepository->delete($id);

        if (!$productDelete) {
            return Response::json([
                'message' => 'Product could not be deleted',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json([
            'message' => 'Product has been deleted',
        ], HttpResponse::HTTP_OK);
    }
}
