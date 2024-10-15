@extends('backend.layouts.app')

<style>
    .wrap-text {
        white-space: normal;
        word-wrap: break-word;
        max-width: 200px;
    }
    #loading {
        text-align: center;
        margin-top: 20px;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }
    .loader {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  gap: 5px;
}

.loading-text {
  color: white;
  font-size: 14pt;
  font-weight: 600;
  margin-left: 0px;
}

.dot {
  margin-left: 3px;
  animation: blink 1.5s infinite;
}
.dot:nth-child(2) {
  animation-delay: 0.3s;
}

.dot:nth-child(3) {
  animation-delay: 0.6s;
}

.loading-bar-background {
  --height: 30px;
  display: flex;
  align-items: center;
  box-sizing: border-box;
  padding: 5px;
  width: 100%;
  height: var(--height);
  background-color: #212121 /*change this*/;
  box-shadow: #0c0c0c -2px 2px 4px 0px inset;
  border-radius: calc(var(--height) / 2);
}

.loading-bar {
  position: relative;
  display: flex;
  justify-content: center;
  flex-direction: column;
  --height: 20px;
  width: 100%;
  height: var(--height);
  overflow: hidden;
  background: rgb(222, 74, 15);
  background: linear-gradient(
    0deg,
    rgba(222, 74, 15, 1) 0%,
    rgba(249, 199, 79, 1) 100%
  );
  border-radius: calc(var(--height) / 2);
  animation: loading 4s ease-out infinite;
}

.white-bars-container {
  position: absolute;
  display: flex;
  align-items: center;
  gap: 18px;
}

.white-bar {
  background: rgb(255, 255, 255);
  background: linear-gradient(
    -45deg,
    rgba(255, 255, 255, 1) 0%,
    rgba(255, 255, 255, 0) 70%
  );
  width: 10px;
  height: 45px;
  opacity: 0.3;
  rotate: 45deg;
}

@keyframes loading {
  0% {
    width: 0;
  }
  80% {
    width: 100%;
  }
  100% {
    width: 100%;
  }
}

@keyframes blink {
  0%,
  100% {
    opacity: 0;
  }
  50% {
    opacity: 1;
  }
}

#status {
            padding: 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .in-progress {
            background-color: orange;
        }
        .completed {
            background-color: green;
        }
        .not-started {
            background-color: gray; /* Warna untuk status 'Not Started' */
        }


</style>

@section('content')
{{-- @if($jobMessage)
    <div class="alert alert-info">
        {{ $jobMessage }}
    </div>
@endif --}}
<div id="loading" style="display: none;">
    <p>Scanning websites...</p>
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>Monitoring Website</h5></br>

                <h3 id="clock"></h3>
                <div id="" style="display: block; position: fixed; top: 20px; right: 20px; background: #007bff; color: white; padding: 10px; border-radius: 5px;">

                </div>
            </div>

            <script>
                // Fungsi untuk mengupdate waktu secara live
                function updateClock() {
                    var now = new Date(); // Mendapatkan waktu saat ini

                    // Mengatur opsi untuk format waktu
                    var timeOptions = {
                        timeZone: 'Asia/Jakarta',
                        hour12: false,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    };

                    // Mengatur opsi untuk format tanggal
                    var dateOptions = {
                        timeZone: 'Asia/Jakarta',
                        weekday: 'long', // Mengambil nama hari
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };

                    var formattedTime = now.toLocaleTimeString('id-ID', timeOptions); // Format waktu
                    var formattedDate = now.toLocaleDateString('id-ID', dateOptions); // Format tanggal

                    // Menampilkan waktu dan tanggal dalam elemen dengan ID 'clock'
                    document.getElementById('clock').textContent = formattedDate + ', ' + formattedTime;
                }

                // Panggil fungsi updateClock setiap detik
                setInterval(updateClock, 1000);
            </script>
            <div class="ibox-content">
                <!-- Separate buttons for checking websites -->
                <button id="checkAllWebsitesBtn" class="btn btn-primary">Check All Websites</button>
                <button id="check-slot-btn" class="btn btn-warning">Check Infected Websites</button>
                {{-- <button id="check-status-btn" class="btn btn-warning">{{ $status_job }}</button> --}}
                <h1>Website Scan Progress</h1>

                @php
                    $totalWebsites = \App\Models\Website::count();
                    $processedWebsites = Cache::get('processed_websites', 0);
                @endphp

                <div>
                    <p id="progress-text">Processed Websites: {{ $processedWebsites }} / {{ $totalWebsites }}</p>
                </div>

                <div id="progress-bar" style="width: 100%; background: #f0f0f0; border: 1px solid #ccc; margin-top: 10px;">
                    <div id="progress-fill" style="width: {{ ($processedWebsites / $totalWebsites) * 100 }}%; height: 30px; background: #4caf50;"></div>
                </div>
                <script>
                    // Function to fetch the scan progress
                    function fetchProgress() {
                        $.get('{{ route("backend.scan_progress") }}', function(data) {
                            const processed = data.processed;
                            const total = data.total;

                            // Update the progress text and bar
                            $('#progress-text').text(`Processed Websites: ${processed} / ${total}`);
                            $('#progress-fill').css('width', (processed / total) * 100 + '%');

                            // Stop the interval if processing is complete
                            if (processed >= total) {
                                clearInterval(progressInterval);
                            }
                        });
                    }

                    // Set an interval to fetch progress every 2 seconds
                    const progressInterval = setInterval(fetchProgress, 2000);
                </script>

    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
    <script>
        let previousStatus = ''; // Variabel untuk melacak status sebelumnya

        function fetchMonitoringStatus() {
            fetch('{{ route("backend.monitoring.getJobStatus") }}')
                .then(response => response.json())
                .then(data => {
                    console.log(data);  // Tampilkan data di konsol
                    const statusDiv = document.getElementById('status');

                    // Reset state
                    statusDiv.classList.remove('in-progress', 'completed', 'not-started');

                    // Tentukan pesan dan kelas berdasarkan status
                    if (data.status_job === 'In progress') {
                        // Gantikan dengan teks "Website Sedang Dalam Pengecekan" beserta animasi titik
                        statusDiv.innerHTML = `
                            Website Sedang Dalam Pengecekan<span class="dot">.</span><span class="dot">.</span><span class="dot">.</span>
                        `;
                        statusDiv.classList.add('in-progress');
                    } else if (data.status_job === 'completed') {
                        statusDiv.innerText = 'Pengecekan Website Selesai';
                        statusDiv.classList.add('completed');

                        // Cek jika status berubah dari "In progress" ke "completed"
                        if (previousStatus === 'In progress' && data.status_job === 'completed') {
                            // Refresh halaman setelah 2 detik
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    } else {
                        statusDiv.innerText = 'Belum ada pengecekan';
                        statusDiv.classList.add('not-started');
                    }

                    // Simpan status saat ini sebagai status sebelumnya untuk pengecekan berikutnya
                    previousStatus = data.status_job;
                })
                .catch(error => console.error('Error fetching status:', error));
        }

        // Jalankan setiap 5 detik
        setInterval(fetchMonitoringStatus, 5000);
    </script>



<div id="status" class="loading-text">Loading status...</div>


            <div class="row">
                <div class="col-sm-4">
                    <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-xs-4">
                                <i class="fa fa-arrow-up fa-5x"></i>
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
                                <i class="fa fa-arrow-down fa-5x"></i>
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
                                <h2 class="font-bold"><span id="results2Count">0</span> Website</h2>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="ibox-content">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#monitoringResults"> Monitoring All Website</a></li>
                        <li><a data-toggle="tab" href="#slotcheckresult"> Infected Website</a></li>
                        <li><a data-toggle="tab" href="#responseTimeResults"> Response Time</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="monitoringResults" class="tab-pane active">
                            <div class="project-list">
                                {{-- @if (isset($results) && is_array($results) && count($results) > 0) --}}
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
                                                    <td class="wrap-text">{{ $result['url'] }}</td>
                                                    <td >
                                                        @if($result['status'] =='up')
                                                        <img src="{{ URL::to('image/Green_circle.gif') }}" style="height:20px; width:20px">
                                                                            @endif
                                                        @if($result['status'] !='up')
                                                        <img src="{{ URL::to('image/Red_circle.gif') }}" style="height:20px; width:20px">
                                                                            @endif
                                                                            {{ $result['status'] }}
                                                                        </td>
                                                    <td>{{ $result['ip_address'] }}</td>
                                                    <td>{{ $result['ssl_status'] }}</td>
                                                    <td>{{ $result['ssl_expiry_date'] }}</td>
                                                    <td>{{ $result['checked_at'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $results->links() }}
                                {{-- @else
                                    <div class="alert alert-warning" role="alert">
                                        No results available or results are not in the expected format.
                                    </div>
                                @endif --}}
                            </div>
                        </div>

                        <div id="responseTimeResults" class="tab-pane fade">
                            <div class="project-list">
                                {{-- @if (isset($results3) && is_array($results3) && count($results3) > 0) --}}
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
                                            @foreach($results2 as $website)
            <tr>
                <td>{{ $website->url }}</td>
                <td>{{ $website->websiteStatus->status ?? 'N/A' }}</td>
                <td>{{ $website->websiteStatus->ip_address ?? 'N/A' }}</td> <!-- Status from WebsiteStatus -->
                <td>{{ $website->websiteStatus->response_time ?? 'N/A' }}</td> <!-- Response time from WebsiteStatus -->
                <td>{{ $website->websiteStatus->ssl_status ?? 'N/A' }}</td> <!-- SSL status from WebsiteStatus -->
                <td>{{ $website->websiteStatus->ssl_expiry_date ?? 'N/A' }}</td> <!-- SSL status from WebsiteStatus -->
                <td>{{ $website->websiteStatus->checked_at ?? 'N/A' }}</td> <!-- SSL status from WebsiteStatus -->
            </tr>
        @endforeach
                                        </tbody>
                                    </table>
                                {{-- @else
                                    <div class="alert alert-warning" role="alert">
                                        No results available or results are not in the expected format.
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                        <div id="slotcheckresult" class="tab-pane fade">
                            <div class="project-list">
                                <div id="slot-check-result" style="margin-top: 20px;"></div>

        <!-- List of Websites -->
        <ul id="website-list" style="margin-top: 20px;"></ul>

                            </div>
                        </div>

                    </div>
                </div>
                <div id="notification" style="display: none; position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 10px; border-radius: 5px;">
                    Job selesai!
                </div>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


                <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
                <script>
                    document.getElementById('checkAllWebsitesBtn').addEventListener('click', function() {
                        window.location.reload(); // Melakukan refresh halaman
                    });
                </script>
                <script>
                    let isChecking = true;

                    function fetchAllWebsites() {
                        $('#loading').show(); // Menampilkan loading
                        $.ajax({
                            url: '{{ route("backend.monitoring.check") }}',
                            method: 'GET',
                            success: function() {
                                // Menyembunyikan loading setelah permintaan berhasil
                                $('#loading').hide();
                                isChecking = true; // Mulai memeriksa status job
                            },
                            error: function() {
                                $('#loading').hide(); // Menyembunyikan loading jika terjadi error
                                alert('Error fetching websites. Please try again.');
                            }
                        });
                    }

                    let isChecking = false; // Ganti dengan status Anda

function fetchJobStatus() {
    $.ajax({
        url: '{{ route("backend.monitoring.getJobStatus") }}',
        method: 'GET',
        success: function(data) {
            if (data.status === 'in_progress') {
                isChecking = true;
                $('#checkAllWebsitesBtn').prop('disabled', true); // Nonaktifkan tombol
            } else if (data.status === 'completed') {
                isChecking = false;
                $('#checkAllWebsitesBtn').prop('disabled', false); // Aktifkan tombol
                fetchResults(); // Ambil hasil jika sudah selesai
            }
        },
        error: function() {
            console.error('Error fetching job status.');
        }
    });
}

$(document).ready(function() {
    $('#checkAllWebsitesBtn').click(function() {
        fetchAllWebsites();
    });
});

// Polling untuk memeriksa status setiap 5 detik
setInterval(function() {
    if (isChecking) {
        fetchJobStatus();
    }
}, 5000);

                </script>

<script>
    $(document).ready(function() {
        // Ketika tombol "Check Slot" diklik
        $('#check-slot-btn').on('click', function() {
            // Lakukan request AJAX
            $.ajax({
                url: '{{ route("backend.monitoring.check-slot") }}',
                type: 'GET',
                success: function(response) {
                    // Bersihkan list sebelumnya
                    $('#website-list').empty();

                    if (response.found) {
                        // Tampilkan daftar website yang ditemukan
                        $('#slot-check-result').html('<div class="alert alert-success">Text "slot" ditemukan di beberapa website berikut:</div>');

                        response.websites.forEach(function(website) {
                            $('#website-list').append('<li><a href="' + website + '" target="_blank">' + website + '</a></li>');
                        });

                        // Update total infected website count
                        $('#results2Count').text(response.websiteCount);
                    } else {
                        $('#slot-check-result').html('<div class="alert alert-danger">Text "slot" tidak ditemukan di website ciamiskab.go.id</div>');

                        // Set infected website count to 0
                        $('#results2Count').text(0);
                    }
                },
                error: function() {
                    // Tampilkan pesan error jika request gagal
                    $('#slot-check-result').html('<div class="alert alert-warning">Terjadi kesalahan saat melakukan pengecekan.</div>');

                    // Set infected website count to 0
                    $('#results2Count').text(0);
                }
            });
        });
    });
</script>


            </div>
        </div>
    </div>
</div>
@endsection
