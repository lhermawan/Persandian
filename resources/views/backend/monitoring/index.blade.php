@extends('backend.layouts.app')

<style>
    .wrap-text {
        white-space: normal;
        word-wrap: break-word;
        max-width: 200px;
    }
</style>

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Monitoring Website</h5>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-globe fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> TOTAL Website UP</span>
                                <h2 class="font-bold" id="upCount">{{ $upCount }} WEBSITE</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-globe fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> TOTAL WEBSITE DOWN </span>
                                <h2 class="font-bold" id="downCount">{{ $downCount }} Website</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-globe fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <span> TOTAL INFECTED WEBSITE </span>
                                <h2 class="font-bold">{{ $results2Count }} Website</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ibox-content">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#monitoringResults"> Monitoring All Website</a></li>
                        <li><a data-toggle="tab" href="#additionalResults"> Infected Website</a></li>
                        <li><a data-toggle="tab" href="#responseTimeResults"> Response Time</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="monitoringResults" class="tab-pane active">
                            <div class="project-list">
                                @if (isset($results) && is_array($results) && count($results) > 0)
                                    <table id="resultsTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>URL</th>
                                                <th>Status</th>
                                                <th>IP Address</th>
                                                <th>SSL Status</th>
                                                <th>SSL Expiry Date</th>
                                                <th>Checked At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $result)
                                                <tr>
                                                    <td class="wrap-text">{{ htmlspecialchars($result['url']) }}</td>
                                                    <td>{{ htmlspecialchars($result['status']) }}</td>
                                                    <td>{{ htmlspecialchars($result['ip_address']) }}</td>
                                                    <td>{{ htmlspecialchars($result['ssl_status']) }}</td>
                                                    <td>{{ htmlspecialchars($result['ssl_expiry_date']) }}</td>
                                                    <td>{{ htmlspecialchars($result['checked_at']) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        No results available or results are not in the expected format.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div id="responseTimeResults" class="tab-pane fade">
                            <div class="project-list">
                                @if (isset($results3) && is_array($results3) && count($results3) > 0)
                                    <table id="responseTimeTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>URL</th>
                                                <th>Status</th>
                                                <th>IP Address</th>
                                                <th>Response Time</th>
                                                <th>SSL Status</th>
                                                <th>SSL Expiry Date</th>
                                                <th>Checked At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results3 as $result)
                                                <tr>
                                                    <td class="wrap-text">{{ htmlspecialchars($result['url']) }}</td>
                                                    <td>{{ htmlspecialchars($result['status']) }}</td>
                                                    <td>{{ htmlspecialchars($result['ip_address']) }}</td>
                                                    <td>{{ htmlspecialchars($result['response_time']) }}</td>
                                                    <td>{{ htmlspecialchars($result['ssl_status']) }}</td>
                                                    <td>{{ htmlspecialchars($result['ssl_expiry_date']) }}</td>
                                                    <td>{{ htmlspecialchars($result['checked_at']) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        No results available or results are not in the expected format.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div id="additionalResults" class="tab-pane fade">
                            <p>Results from Search: <span id="results2Count">0</span></p>
                            <table id="results2Table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>URL</th>
                                        <th>Checked At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results2 as $result)
                                        <tr>
                                            <td class="wrap-text">{{ htmlspecialchars($result['url']) }}</td>
                                            <td>{{ htmlspecialchars($result['checked_at']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

                <script>
                    // AJAX function
let isFetching = false;

function fetchStatus() {
    if (isFetching) return; // Prevent multiple requests
    isFetching = true;

    $.ajax({
        url: '{{ route("backend.monitoring.latest") }}', // Correct route
        method: 'GET',
        success: function(data) {
            // Update Up and Down Counts
            $('#upCount').text(data.upCount);
            $('#downCount').text(data.downCount);

            // Clear and Populate Results Table
            $('#resultsTable tbody').empty();
            $.each(data.results, function(index, result) {
                $('#resultsTable tbody').append(`
                    <tr>
                        <td class="wrap-text">${result.url}</td>
                        <td>${result.status}</td>
                        <td>${result.ip_address}</td>
                        <td>${result.ssl_status}</td>
                        <td>${result.response_time}</td>
                        <td>${result.checked_at}</td>
                    </tr>
                `);
            });

            // Update Results 2 Count
            $('#results2Count').text(data.results2Count);
            $('#results2Table tbody').empty();
            $.each(data.results2, function(index, result) {
                $('#results2Table tbody').append(`
                    <tr>
                        <td class="wrap-text">${result.url}</td>
                        <td>${result.checked_at}</td>
                    </tr>
                `);
            });

            // Initialize DataTables for pagination
            if ($.fn.DataTable) {
                $('#resultsTable').DataTable().clear().destroy(); // Clear and destroy previous instance
                $('#results2Table').DataTable().clear().destroy();
                $('#resultsTable').DataTable();
                $('#results2Table').DataTable();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching status:', error); // Log the error
            alert('An error occurred while fetching the data. Please try again.');
        },
        complete: function() {
            isFetching = false; // Reset the fetching state
        }
    });
}

// Call fetchStatus on page load and set interval for real-time updates
$(document).ready(function() {
    fetchStatus(); // Initial fetch
    setInterval(fetchStatus, 1800000); // Fetch status every 5 seconds
});


                </script>
            </div>
        </div>
    </div>
</div>
@endsection
