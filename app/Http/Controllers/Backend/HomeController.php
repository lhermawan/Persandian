<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Mart_booking;
use App\Models\Mart_poliklinik;
use App\Models\Mart_users;
use App\Models\Desa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
//        dd(Carbon::now()->month);
        $data['cari'] = $request->get('cari');
        $data['tte'] =Desa::where('tte','=','TTE')->get();
//        print_r($data['tte']);die;
//        $wordlist = Wordlist::where('id', '<=', $correctedComparisons)->get();
        $data['totaltte'] = $data['tte']->count();

        $data['website'] =Desa::where('website','!=',null)->get();
        $data['totalwebsite'] = $data['website']->count();

        $data['sosialisasi'] =Desa::where('sosialisasi','!=',null)->get();
        $data['totalsosialisasi'] = $data['sosialisasi']->count();

        $data['hdiskominfo'] =Desa::where('jenis','!=','Luar')->get();
        $data['hdiskominfo'] = $data['hdiskominfo']->count();

        $data['hluar'] =Desa::where('jenis','=','Luar')->get();
        $data['hluar'] = $data['hluar']->count();

        $data['hweb'] =Desa::where('jenis','=',null)->get();
        $data['hweb'] = $data['hweb']->count();





        $data['durasi'] = $request->get('durasi');

        $data['userweek'] = Mart_users::where('week', Carbon::now()->weekOfYear)->first();
        $data['usermonth'] = Mart_users::where('month', Carbon::now()->month)->sum('jumlahregistrasi');
        $data['userlastmonth'] = Mart_users::where('month', Carbon::now()->subMonth()->format('n'))->sum('jumlahregistrasi');
        $data['usermonthavg'] = $data['usermonth'] / 30;

        $data['lastsixmonth'] = Mart_booking::where('create_date', '>=', Carbon::now()->subMonths(6))->sum('jumlahbooking');
        $data['lastsixmonthcancel'] = Mart_booking::where('create_date', '>=', Carbon::now()->subMonths(6))->sum('jumlahcancel');
        $data['lastsixmonthconfirm'] = Mart_booking::where('create_date', '>=', Carbon::now()->subMonths(6))->sum('jumlah_konfirmasi_akan_datang_user');
        $data['lastsixmonthsuccessconfirm'] = Mart_booking::where('create_date', '>=', Carbon::now()->subMonths(6))->sum('jumlah_konfirmasi_kedatangan_walk_in');
        $data['lastsixmonthtotal'] = $data['lastsixmonth'] + $data['lastsixmonthcancel'] + $data['lastsixmonthconfirm'] + $data['lastsixmonthsuccessconfirm'];

        $data['last_month_pemesanan'] = Mart_booking::where('month', Carbon::now()->subMonth()->format('n'))->sum('jumlahbooking');
        $data['last_month_batal'] = Mart_booking::where('month', Carbon::now()->subMonth()->format('n'))->sum('jumlahcancel');
        $data['last_month_menunggu_konfirmasi'] = Mart_booking::where('month', Carbon::now()->subMonth()->format('n'))->sum('jumlah_konfirmasi_akan_datang_user');
        $data['last_month_telah_dikonfirmasi'] = Mart_booking::where('month', Carbon::now()->subMonth()->format('n'))->sum('jumlah_konfirmasi_kedatangan_walk_in');
        $data['last_month_total'] = $data['last_month_pemesanan'] + $data['last_month_batal'] + $data['last_month_menunggu_konfirmasi'] + $data['last_month_telah_dikonfirmasi'];

        $data['current_month_pemesanan'] = Mart_booking::where('month', Carbon::now()->month)->sum('jumlahbooking');
        $data['current_month_batal'] = Mart_booking::where('month', Carbon::now()->month)->sum('jumlahcancel');
        $data['current_month_menunggu_konfirmasi'] = Mart_booking::where('month', Carbon::now()->month)->sum('jumlah_konfirmasi_akan_datang_user');
        $data['current_month_telah_dikonfirmasi'] = Mart_booking::where('month', Carbon::now()->month)->sum('jumlah_konfirmasi_kedatangan_walk_in');
        $data['current_month_total'] = $data['current_month_pemesanan'] + $data['current_month_batal'] + $data['current_month_menunggu_konfirmasi'] + $data['current_month_telah_dikonfirmasi'];
        $data['current_month_avg'] = $data['current_month_total'] / 24;

        $data['weekly'] = [];
        $data['weekbk'] = [];
        $data['weekcl'] = [];
        for ($x = 5; $x >= 0; $x--) {
            $weekly = (date("W")) - $x;
            $booking2 = Mart_booking::where('week', $weekly)->first();
            if ($booking2) {
                $hasilbooking2 = $booking2['jumlahbooking'];
                $hasilcancel2 = $booking2['jumlahcancel'];
            } else {
                $hasilbooking2 = 0;
                $hasilcancel2 = 0;
            }

            array_push($data['weekly'], [$weekly]);
            array_push($data['weekbk'], $hasilbooking2);
            array_push($data['weekcl'], $hasilcancel2);
        }
        $data['week'] = json_encode($data['weekly']);
        $data['weekbk'] = json_encode($data['weekbk']);
        $data['weekcl'] = json_encode($data['weekcl']);

        if ($data['durasi'] == '') {
            $data['durasi'] = 'week';
        }

        if ($data['durasi'] == 'week') {
            $durasi = Carbon::now()->weekOfYear;
        }

        if ($data['durasi'] == 'month') {
            $durasi = Carbon::now()->month;
        }

        if ($data['durasi'] == 'year') {
            $durasi = Carbon::now()->year;
        }


//        DB::enableQueryLog();
        $data['poliklinik'] = Mart_poliklinik::where($data['durasi'], $durasi)
            ->join('poliklinik', 'poliklinik.id', 'mart_poliklinik.idpoliklinik')
            ->join('dokter', 'dokter.id', 'mart_poliklinik.iddokter')
            ->where(function ($query) use ($data) {
                $query->Where('poliklinik.namapoliklinik', 'LIKE', '%' . $data['cari'] . '%')
                    ->orWhere('dokter.namalengkap', 'LIKE', '%' . $data['cari'] . '%');
            })
            ->paginate(10);
//        dd(DB::getQueryLog());
        $data['poliklinik']->appends($request->all());

        $data['datakunjungan'] = DB::select('call jumlahkunjungan_pasien(?)', [
            9,
        ]);

        return view('backend.home', $data);
    }
}
