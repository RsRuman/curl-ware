<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function all(Request $request);
    public function find(int $id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
