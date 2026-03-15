<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ImportProductsJob;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $products = $request->input('products');

        if (!$products) {
            return response()->json(['error' => 'No data received'], 400);
        }

        ImportProductsJob::dispatch($products);

        return response()->json(['message' => 'Import in queue!'], 202);
    }
}
