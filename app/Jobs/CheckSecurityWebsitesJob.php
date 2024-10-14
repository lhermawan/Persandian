<?php

namespace App\Jobs;

use App\Events\WebsiteCheckStatusUpdated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\WebsiteStatus; // Import the model
use App\Models\Website;
use App\Models\Response;
use Illuminate\Support\Facades\Cache;


class CheckSecurityWebsitesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
{
    Cache::put('website_check_status', 'In progress');
    Log::info('Broadcasting website check status', ['status' => 'In progress']);

    // Initialize processed websites count
    Cache::put('processed_websites', 0);

    $chunkedWebsites = Website::pluck('url')->chunk(50);

    foreach ($chunkedWebsites as $chunk) {
        Log::info('Checking websites chunk', ['chunk' => $chunk]);

        try {
            $response = Http::timeout(600)->asForm()->post('http://localhost:5000/vulnerability_scan', [
                'websites' => $chunk->implode(' '),
            ]);

            if ($response->failed()) {
                Log::error('Failed to connect to API', ['status' => $response->status()]);
                continue;
            }

            Log::info('API Response', ['response' => $response->json()]);

            $chunkResults = $response->json();

            foreach ($chunkResults as $result) {
                Log::info('Saving website status', [
                    'url' => $result['url'],
                    'status' => strtolower($result['status']),
                    'ip_address' => $result['ip_address'],
                    'ssl_status' => $result['ssl_status'],
                    'ssl_expiry_date' => $result['ssl_expiry_date'],
                    'response_time' => $result['response_time'],
                ]);

                try {
                    WebsiteStatus::updateOrCreate(
                        ['url' => $result['url']],
                        [
                            'status' => strtolower($result['status']),
                            'ip_address' => $result['ip_address'],
                            'ssl_status' => $result['ssl_status'],
                            'ssl_expiry_date' => $result['ssl_expiry_date'],
                            'response_time' => $result['response_time'],
                            'checked_at' => $result['checked_at'],
                        ]
                    );

                    // Increment processed websites count
                    $processedWebsites = Cache::increment('processed_websites');
                    Log::info('Saved website status successfully', [
                        'url' => $result['url'],
                        'processed' => $processedWebsites,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error saving website status', [
                        'url' => $result['url'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Error during HTTP request', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    Log::info('Website check job completed');
    Cache::put('website_check_status', 'completed');
    Log::info('Broadcasting website check status', ['status' => 'completed']);
}
}
