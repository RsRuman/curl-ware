<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderRepository implements OrderRepositoryInterface
{
    public function all(Request $request): LengthAwarePaginator
    {
        $perPage = $request->query('per_page', 20);

        return Order::query()->paginate($perPage);
    }

    public function myOrders(Request $request): LengthAwarePaginator
    {
        $perPage = $request->query('per_page', 20);

        return Order::query()->where('user_id', Auth::user()->id)->paginate($perPage);
    }

    public function findMyOrder($id)
    {
        return Order::query()->where('user_id', Auth::user()->id)->find($id);
    }

    public function find($id)
    {
        return Order::query()->find($id);
    }

    /**
     * @throws Exception
     */
    public function create(array $data)
    {
        $order =  Order::create([
            'user_id'       => Auth::user()->id,
            'grand_total'   => $data['total_price'],
            'shipping_cost' => $data['shipping_cost'],
            'discount'      => $data['discount']
        ]);

        if (!$order) {
            throw new Exception('Could not create order');
        }

        foreach ($data['items'] as $item) {
            $orderDetail = $order->orderDetails()->create([
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['unit_price'],
            ]);

            if (!$orderDetail) {
                throw new Exception('Could not create order detail');
            }
        }

        return $order;
    }

    public function update(array $data, $id)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
