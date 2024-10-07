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
  width: 350px;
  height: 180px;
  border-radius: 10px;
  background: #fff;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-evenly;
  padding: 30px;
  box-shadow: 2px 2px 10px -5px lightgrey;
}
.loading {
  width: 100%;
  height: 10px;
  background: lightgrey;
  border-radius: 10px;
  position: relative;
  overflow: hidden;
}
.loading::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 50%;
  height: 10px;
  background: #002;
  border-radius: 10px;
  z-index: 1;
  animation: loading 0.6s alternate infinite;
}
label {
  color: #002;
  font-size: 18px;
  animation: bit 0.6s alternate infinite;
}

@keyframes bit {
  from {
    opacity: 0.3;
  }
  to {
    opacity: 1;
  }
}

@keyframes loading {
  0% {
    left: -25%;
  }
  100% {
    left: 70%;
  }
  0% {
    left: -25%;
  }
}

</style>

@section('content')
@if($jobMessage)
    <div class="alert alert-info">
        {{ $jobMessage }}
    </div>
@endif
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
                <button id="check-status-btn" class="btn btn-warning">{{ $status_job }}</button>
            </div>

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
                                                        <img src="{{ URL::to('image/Green_circle.gif') }}" style="height:7%; width:7%">
                                                                            @endif
                                                        @if($result['status'] !='up')
                                                        <img src="{{ URL::to('image/Red_circle.gif') }}" style="height:7%; width:7%">
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
                <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script src="https://js.pusher.com/7.0/pusher-js.min.js"></script>
<script>
    // Inisialisasi Pusher
    const pusher = new Pusher('7f292e545137046c29e1', {
        cluster: 'ap1',
        encrypted: true
    });

    const channel = pusher.subscribe('job-status');

    channel.bind('App\\Events\\JobCompleted', function(data) {
        // Tampilkan notifikasi pop-up
        const notification = document.getElementById('notification');
        notification.style.display = 'block';
        notification.textContent = data.message;

        // Sembunyikan notifikasi setelah beberapa detik
        setTimeout(function() {
            notification.style.display = 'none';
        }, 5000);
    });
</script>
                <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

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

                    function fetchJobStatus() {
                        $.ajax({
                            url: '{{ route("backend.monitoring.getJobStatus") }}',
                            method: 'GET',
                            success: function(data) {
                                if (data.status === 'completed') {
                                    isChecking = false;
                                    fetchResults();
                                }
                            },
                            error: function() {
                                console.error('Error fetching job status.');
                            }
                        });
                    }

                    // function fetchResults() {
                    //     $.ajax({
                    //         url: '{{ route("backend.monitoring.getResults") }}', // Tambahkan route untuk ambil hasil
                    //         method: 'GET',
                    //         success: function(data) {
                    //             // Render results di tabel
                    //             $('#resultsTable tbody').empty();
                    //             if (data.results.length > 0) {
                    //                 data.results.forEach(function(result) {
                    //                     $('#resultsTable tbody').append(`
                    //                         <tr>
                    //                             <td class="wrap-text">${result.url}</td>
                    //                             <td>${result.status}</td>
                    //                             <td>${result.ip_address}</td>
                    //                             <td>${result.ssl_status}</td>
                    //                             <td>${result.ssl_expiry_date}</td>
                    //                             <td>${result.checked_at}</td>
                    //                         </tr>
                    //                     `);
                    //                 });
                    //             } else {
                    //                 $('#resultsTable tbody').append(`
                    //                     <tr>
                    //                         <td colspan="6" class="text-center">No results available.</td>
                    //                     </tr>
                    //                 `);
                    //             }
                    //         },
                    //         error: function() {
                    //             console.error('Error fetching results.');
                    //         }
                    //     });
                    // }

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
