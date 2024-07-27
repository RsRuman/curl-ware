<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function all(Request $request): Collection
    {
        return Category::query()->with('childs')->get();
    }

    public function find($id)
    {
        return Category::query()->with('childs')->find($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update($id, array $data): bool
    {
        return Category::query()->find($id)->update($data);
    }

    public function delete($id): bool
    {
        return Category::query()->find($id)->delete();
    }
}
