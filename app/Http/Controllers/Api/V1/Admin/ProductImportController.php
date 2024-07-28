<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductImportRequest;
use App\Jobs\ProductImportJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as HttpResponse;

class ProductImportController extends Controller
{
    public function import(ProductImportRequest $request): JsonResponse
    {
        $categories = $request->input('categories');

        // Store the uploaded file temporarily
        $path = $request->file('file')->store('imports');

        ProductImportJob::dispatch($path, $categories);

        return Response::json([
            'message' => 'Products are importing in queue',
        ], HttpResponse::HTTP_OK);
    }
}
