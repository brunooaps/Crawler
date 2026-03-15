<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ScrapeProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:products';

    /**
     * The console command description.
     *
     * @var string
     */
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
            return [
                'title'       => $node->filter('h4.title')->text(),
                'price'       => $node->filter('.price-wrapper')->text(),
                'image_url'   => $node->filter('img')->attr('src'),
                'description' => $node->filter('.description')->count() ? $node->filter('.description')->text() : null,
                'category'    => 'General',
            ];
        });

        if (empty($data)) {
            $this->error('No products found. Check your CSS selectors.');
            return;
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
