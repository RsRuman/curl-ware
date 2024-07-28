<?php

namespace App\Jobs;

use AllowDynamicProperties;
use App\Interfaces\ProductImportInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

#[AllowDynamicProperties]
class ProductImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $categories)
    {
        $this->filePath   = $filePath;
        $this->categories = $categories;
    }

    /**
     * Execute the job.
     */
    public function handle(ProductImportInterface $productImportService): void
    {
        $productImportService->csv($this->filePath, $this->categories);
    }
}
