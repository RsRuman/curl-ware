<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function all(Request $request);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
