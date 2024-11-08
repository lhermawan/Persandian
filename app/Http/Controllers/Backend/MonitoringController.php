<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Website;
use App\Models\WebsiteStatus;
use Illuminate\Support\Facades\Log;
use App\Services\GoogleSearchService;
use App\Services\MonitoringService;
class MonitoringController extends Controller
{
    protected $searchService;

    public function __construct(GoogleSearchService $searchService)
    {
        $this->searchService = $searchService;
    }
    // Function to display the monitoring page without automatic checks
    public function index(Request $request)
{
    // Ambil semua hasil pengecekan dari database
    $resultscount = WebsiteStatus::orderBy('status', 'asc')->get();
    $results = WebsiteStatus::orderBy('status', 'asc')->paginate(10);

    // Get websites with status = 1 and their corresponding WebsiteStatus
    $results2 = Website::where('status', '1')
        ->with('websiteStatus') // Assuming there's a relationship defined
        ->get();

    // Status job from cache
    $status_job = cache('website_check_status', 'Not started');
// Ambil total website
$totalWebsites = Website::count();

// Definisikan chunk size dan timeout per chunk
$chunkSize = 50; // Website per chunk
$timeoutPerChunk = 600; // Detik (10 menit per chunk)

// Hitung jumlah chunk
$totalChunks = ceil($totalWebsites / $chunkSize);

// Hitung estimasi waktu dalam detik
$estimatedTimeInSeconds = $totalChunks * $timeoutPerChunk;

// Konversi waktu menjadi jam, menit, detik
$hours = floor($estimatedTimeInSeconds / 3600);
$minutes = floor(($estimatedTimeInSeconds % 3600) / 60);
$seconds = $estimatedTimeInSeconds % 60;



    // Cek apakah ini permintaan AJAX
    if ($request->ajax()) {
        return response()->json([
            'status_job' => $status_job,
            'results' => $results,
            'upCount' => $resultscount->where('status', 'up')->count(),
            'downCount' => $resultscount->where('status', '!=', 'up')->count(),
            'results2' => $results2,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,

        ]);
    }

    // Menentukan pesan berdasarkan status pekerjaan
    $jobMessage = null;
    if ($status_job == 'In progress') {
        $jobMessage = 'The job is currently in progress. Please wait...';
    } elseif ($status_job == 'completed') {
        $jobMessage = 'The job has been completed successfully!';
    }

    // Jika bukan AJAX, tampilkan view biasa
    return view('backend.monitoring.index', [
        'status_job' => $status_job,
        'results' => $results,
        'upCount' => $resultscount->where('status', 'up')->count(),
        'downCount' => $resultscount->where('status', '!=', 'up')->count(),
        'results2' => $results2,
        'results3' => [], // Sesuaikan data
        'jobMessage' => $jobMessage,
        'hours' => $hours,
        'minutes' => $minutes,
        'seconds' => $seconds,// Pesan status pekerjaan
    ]);
}

    // Function to call the Flask API to check website status
    public function checkWebsites(Request $request)
{
    // Dispatch the job to the queue
    \App\Jobs\CheckWebsitesJob::dispatch();

    return response()->json(['message' => 'Website check started in background'], 200);
}
public function showCheckStatus()
{


    // Kirimkan estimasi waktu ke view
    return view('status.check', compact('hours', 'minutes', 'seconds'));
}
public function checkSlot()
{
    $site = '*.ciamiskab.go.id';
    $text = 'slot';

    // Dapatkan daftar situs yang ditemukan dari service
    $result = $this->searchService->checkSiteIntext($site, $text);

    return response()->json([
        'found' => $result['found'],
        'websites' => $result['websites'],
        'websiteCount' => count($result['websites']) // Menghitung jumlah website
    ]);
}

public function getJobStatus()
{
    // Anda bisa menyimpan status job ke dalam cache atau database
    // Sebagai contoh, jika status tersimpan di cache:
    $status = cache('website_check_status', 'Not started');

    return response()->json(['status_job' => $status]);
}
public function getResults()
{
    // Ambil hasil dari cache
    $results = cache('website_check_results', []);

    return response()->json(['results' => $results]);
}
public function streamStatus()
{
    return response()->stream(function() {
        while (true) {
            echo "data: " . json_encode(MonitoringService::getStatus()) . "\n\n";
            ob_flush();
            flush();
            sleep(5); // Ubah setiap 5 detik
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
    ]);
}
}
