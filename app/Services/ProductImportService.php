<?php

namespace App\Services;

use AllowDynamicProperties;
use App\Interfaces\ProductImportInterface;
use App\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[AllowDynamicProperties]
class ProductImportService implements ProductImportInterface
{
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @throws Exception
     */
    public function csv($filePath, array $categories): void
    {
        // Checking file exist or not
        if (!Storage::exists($filePath)) {
            throw new Exception("File not found");
        }

        $file = fopen(Storage::path($filePath), 'r');

        if (!$file) {
            throw new Exception("Failed to open file: $filePath");
        }

        // Skip the header row
        $header = fgetcsv($file);
        if (!$header) {
            throw new Exception("Failed to read CSV header");
        }

        while ($row = fgetcsv($file)) {
            $data = [
                'name'        => $row[1],
                'slug'        => Str::slug($row[1]),
                'description' => Str::slug($row[2]),
                'price'       => Str::slug($row[3]),
                'status'      => $row[4],
            ];

            $product = $this->productRepository->create($data);

            $product->categories()->attach($categories);
        }

        fclose($file);

        Storage::delete($filePath);
    }
}
