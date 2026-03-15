<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScrapeProducts extends Command
{
    protected $signature = 'scrape:products';

    protected $description = 'Scrape products from Oxylabs Sandbox and send to API';

    public function handle()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'https://sandbox.oxylabs.io/products', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ]
        ]);

        $html = $response->getBody()->getContents();
        $crawler = new Crawler($html);

        $data = $crawler->filter('div.product-card')->each(function ($node) {
            $categories = $node->filter('.category span')->each(function ($span) {
                return trim($span->text());
            });
            $categoryString = implode(', ', $categories);

            $price = $node->filter('.price-wrapper')->count() ? $node->filter('.price-wrapper')->text() : '0';

            $imageUrl = "https://via.placeholder.com/150";
            $noscript = $node->filter('noscript');

            if ($noscript->count() > 0) {
                if (preg_match('/src="([^"]+)"/', $noscript->html(), $matches)) {
                    $path = $matches[1];

                    if (str_starts_with($path, '/')) {
                        $imageUrl = 'https://sandbox.oxylabs.io' . $path;
                    } else {
                        $imageUrl = $path;
                    }
                }
            }

            return [
                'title'       => $node->filter('h4.title')->text(),
                'price'       => $price,
                'image_url'   => $imageUrl,
                'description' => $node->filter('.description')->count() ? $node->filter('.description')->text() : null,
                'category'    => $categoryString ?: 'General',
            ];
        });

        if (empty($data)) {
            $this->error('No products found. Check your CSS selectors.');
            return;
        }

        $jsonData = [
            'products' => $data,
            'exported_at' => now()->toIso8601String(),
            'total_products' => count($data)
        ];

        $jsonContent = json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        $fileName = 'products_' . date('Y-m-d_His') . '.json';
        $filePath = storage_path('app/' . $fileName);
        
        if (file_put_contents($filePath, $jsonContent)) {
            $this->info('JSON file exported successfully: ' . $fileName);
            $this->info('  Location: ' . $filePath);
        } else {
            $this->warn('Failed to save JSON file, but continuing with API import...');
        }

        $response = \Illuminate\Support\Facades\Http::post(config('app.url') . '/api/import', [
            'products' => $data
        ]);

        if ($response->successful()) {
            $this->info(count($data) . ' products found and sent to the import queue.');
        } else {
            $this->error('Failed to send data to the API. Status: ' . $response->status());
        }
    }
}
