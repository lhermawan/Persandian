<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Website;

class MonitoringController extends Controller
{
    // Function to call the Flask API to check website status
    public function index(Request $request)
    {
        // Fetching websites from the database
        $websites = Website::pluck('url')->implode(' '); // Combine URLs into a single string
        $websites2 = []; // Make sure this is initialized properly

        // Sending POST request to Flask API
        $response = Http::timeout(60)->asForm()->post('http://localhost:5000/check', [
            'websites' => $websites,
        ]);

        if ($response->failed()) {
            return back()->withErrors(['api_error' => 'Failed to fetch website statuses.']);
        }

        $response2 = Http::asForm()->post('http://localhost:5000/search', [
            'websites' => $websites2,
        ]);

        if ($response2->failed()) {
            return back()->withErrors(['api_error' => 'Failed to fetch infected websites.']);
        }

        // Decode the JSON response
        $results = $response->json();
        $results2 = $response2->json();

        // Initialize counter for "Up" status
        $upCount = 0;

        // Count the number of "Up" statuses
        foreach ($results as $result) {
            if ($result['status'] === 'Up') {
                $upCount++;
            }
        }

        // Calculate "Down" count
        $downCount = count($results) - $upCount;

        // Count the number of entries in results2
        $results2Count = count($results2);

        // Fetch websites with status = 1 from the database
        $websitesWithStatus1 = Website::where('status', 1)->pluck('url')->toArray();

        // Filter results3 to include only those where status is "Up" or from the database
        $results3 = array_filter($results, function ($result) use ($websitesWithStatus1) {
            return in_array($result['url'], $websitesWithStatus1); // Check if the URL is in the fetched list
        });

        // Return the view with results, counts, and results2 count
        return view('backend.monitoring.index', [
            'results' => $results,
            'results3' => $results3, // Now includes only results where status = 1 from the database
            'upCount' => $upCount,
            'downCount' => $downCount, // Now represents all non-"Up" statuses
            'results2' => $results2,
            'results2Count' => $results2Count,  // Pass the count to the view
        ]);
    }
    public function latest()
    {
        // Fetching websites from the database
        $websites = Website::pluck('url')->implode(' '); // Combine URLs into a single string
        $websites2 = []; // Make sure this is initialized properly

        // Sending POST request to Flask API
        $response = Http::timeout(60)->asForm()->post('http://localhost:5000/check', [
            'websites' => $websites,
        ]);

        $response2 = Http::asForm()->post('http://localhost:5000/search', [
            'websites' => $websites2,
        ]);

        // Decode the JSON response
        $results = $response->json();
        $results2 = $response2->json();

        // Initialize counter for "Up" status
        $upCount = 0;

        // Count the number of "Up" statuses
        foreach ($results as $result) {
            if ($result['status'] === 'Up') {
                $upCount++;
            }
        }

        // Calculate "Down" count
        $downCount = count($results) - $upCount;

        // Count the number of entries in results2
        $results2Count = count($results2);

        // Fetch websites with status = 1 from the database
        $websitesWithStatus1 = Website::where('status', 1)->pluck('url')->toArray();

        // Filter results3 to include only those where status is "Up" or from the database
        $results3 = array_filter($results, function ($result) use ($websitesWithStatus1) {
            return in_array($result['url'], $websitesWithStatus1); // Check if the URL is in the fetched list
        });

        // Return JSON response
        return response()->json([
            'results' => $results,
            'results3' => $results3,
            'upCount' => $upCount,
            'downCount' => $downCount,
            'results2' => $results2,
            'results2Count' => $results2Count,
        ]);
    }
    // Function to call the Flask API to download the Excel file
    public function exportReport()
    {
        // Sending GET request to Flask API
        $response = Http::get('http://localhost:5000/export');

        // Returning the exported file from Flask
        return response($response->body(), 200)
            ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->header('Content-Disposition', 'attachment; filename="website_status_report.xlsx"');
    }
}
