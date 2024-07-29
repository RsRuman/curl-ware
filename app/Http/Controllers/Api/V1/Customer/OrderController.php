<?php

namespace App\Http\Controllers\Api\V1\Customer;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Response;

#[AllowDynamicProperties]
class OrderController extends Controller
{
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * My Orders
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->myOrders($request);
        $orders = OrderResource::collection($orders);
        $orders = $this->collectionResponse($orders);

        return Response::json([
            'message' => 'Orders retrieved successfully',
            'data' => $orders,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * My order
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $order = $this->orderRepository->findMyOrder($id);
        if (!$order) {
            return Response::json([
                'message' => 'Order not found',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        return Response::json([
            'message' => 'Order retrieved successfully',
            'data' => new OrderResource($order->load('orderDetails')),
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Order create
     * @param OrderStoreRequest $request
     * @return JsonResponse
     */
    public function create(OrderStoreRequest $request): JsonResponse
    {
        $data = $request->safe()->only('items', 'shipping_cost', 'discount');

        // Retrieve the product IDs from the items array
        $productIds = array_column($data['items'], 'product_id');

        // Fetch product prices based on the IDs
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $totalPrice = 0;

        foreach ($data['items'] as $key => $item) {
            $product = $products->get($item['product_id']);

            if ($product) {
                // Calculate total price
                $totalPrice += $product->price * $item['quantity'];
                $data['items'][$key]['unit_price'] = $product->price;
            }
        }

        $data['total_price'] = $totalPrice;

        try {
            $order = $this->orderRepository->create($data);
            $order = $order->load('orderDetails');
            $order = new OrderResource($order);

            return response()->json([
                'message' => 'Order created successfully',
                'data' => $order,
            ], HttpResponse::HTTP_CREATED);

        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
