<?php

namespace App\Http\Controllers\Api\V1\Customer;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

#[AllowDynamicProperties]
class ProductController extends Controller
{
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * All products
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
            'data'    => $products,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Show products
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
            'data'    => $product,
        ], HttpResponse::HTTP_OK);
    }
}
