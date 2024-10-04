<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GoogleSearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function checkSiteIntext($site, $text, $page = 0)
{
    $query = 'site:' . $site . ' intext:"' . $text . '"';
    $url = 'https://www.google.com/search?q=' . urlencode($query) . '&start=' . ($page * 10);


    try {
        $response = $this->client->request('GET', $url);
        $body = $response->getBody()->getContents();

        // Ekstrak URL dari hasil pencarian menggunakan regex sederhana
        $websites = [];
        preg_match_all('/<a href="\/url\?q=(https?:\/\/[^\&]+)&amp;/', $body, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $match) {
                // Memastikan bahwa URL berasal dari domain ciamiskab.go.id atau subdomainnya
                if (preg_match('/https?:\/\/([a-z0-9-]+\.)?ciamiskab\.go\.id/', $match)) {
                    $websites[] = $match;
                }
            }
        }

        // Kembalikan daftar URL yang mengandung teks
        return [
            'found' => count($websites) > 0,
            'websites' => $websites
        ];

    } catch (\Exception $e) {
        // Tangani kesalahan (misalnya log error atau kembalikan array kosong)
        return [
            'found' => false,
            'websites' => []
        ];
    }
}
}
