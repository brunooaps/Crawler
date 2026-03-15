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
    protected $signature = 'app:scrape-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->request('GET', 'https://sandbox.oxylabs.io/products');
        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        // Ajuste os seletores conforme o HTML do site
        $data = $crawler->filter('.product-card')->each(function (Crawler $node) {
            return [
                'title' => $node->filter('h4.title')->text(), // Exemplo de seletor
                'price' => (float) str_replace(['$', ','], '', $node->filter('.price')->text()),
                'description' => $node->filter('.description')->count() ? $node->filter('.description')->text() : '',
                'category' => $node->filter('.category')->count() ? $node->filter('.category')->text() : 'Geral',
                'image_url' => $node->filter('img')->attr('src'),
            ];
        });

        // A prova pede para enviar para a API POST /api/import
        Http::post(url('/api/import'), ['products' => $data]);

        $this->info('Dados enviados para a fila de importação!');
    }
}
