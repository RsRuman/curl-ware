<?php

namespace App\Interfaces;

interface ProductImportInterface
{
    public function csv($filePath, array $categories);
}
