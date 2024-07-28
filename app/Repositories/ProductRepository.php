<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(Request $request): LengthAwarePaginator
    {
        $perPage = $request->query('per_page', 20);

        return Product::query()
            ->when($request->query('name'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query('name') . '%');
            })
            ->when($request->query('sort'), function ($q) use ($request) {
                $sort = $request->query('sort');

                match ($sort) {
//                    'best_sell'         => $q->withCount('orderDetails')->orderBy('order_details_count', 'desc'),
                    'price_high_to_low' => $q->orderBy('price', 'desc'),
                    'price_low_to_high' => $q->orderBy('price', 'asc'),
                    default             => null
                };
            })
            ->when($request->query('category'), function ($q) use ($request) {
                $q->whereHas('categories', function ($q) use ($request) {
                    $q->where('category_id', $request->query('category'));
                });
            })
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return Product::query()->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(array $data, $id): bool
    {
        return Product::query()->find($id)->update($data);
    }

    public function delete($id): bool
    {
        return Product::query()->find($id)->delete();
    }
}
