<?php

namespace App\Jobs;

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
use App\Events\JobCompleted;

class CheckWebsitesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
{
    Cache::put('website_check_status', 'In progress');
    Log::info('Starting website check job');

    // Fetch websites and chunk them
    $chunkedWebsites = Website::pluck('url')->chunk(50);

    foreach ($chunkedWebsites as $chunk) {
        Log::info('Checking websites chunk', ['chunk' => $chunk]);

        $response = Http::timeout(600)->asForm()->post('http://localhost:5000/check', [
            'websites' => $chunk->implode(' '),
        ]);

        // Log the raw response for debugging
        Log::info('API Response', ['response' => $response->json()]);

        if ($response->successful()) {
            $chunkResults = $response->json();

            foreach ($chunkResults as $result) {
                // Log each result before saving
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
                    Log::info('Saved website status successfully', ['url' => $result['url']]);
                } catch (\Exception $e) {
                    Log::error('Error saving website status', [
                        'url' => $result['url'],
                        'error' => $e->getMessage(),
                    ]);
                }
            }

        } else {
            Log::error('Failed to fetch website statuses', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);
        }
    }

    Log::info('Website check job completed');
    Cache::put('website_check_status', 'completed');
    event(new JobCompleted('Pengecekan website selesai.'));
}

}
