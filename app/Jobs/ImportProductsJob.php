<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $products) {}

    public function handle(): void
    {
        foreach ($this->products as $productData) {
            DB::transaction(function () use ($productData) {
                $product = Product::updateOrCreate(
                    ['title' => $productData['title']],
                    [
                        'price' => $this->sanitizePrice($productData['price']),
                        'description' => $productData['description'] ?? null,
                        'category' => $productData['category'] ?? 'General',
                    ]
                );

                $product->images()->updateOrCreate([
                    'url' => $productData['image_url']
                ]);
            });
        }
    }

    private function sanitizePrice($price): float
    {
        return (float) preg_replace('/[^0-9.]/', '', str_replace(',', '.', $price));
    }
}
