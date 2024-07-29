<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function all(Request $request);
//    public function myOrders(Request $request);
    public function find($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}
