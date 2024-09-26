<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FlashMessageTraits;
use App\Models\Desa;
use DB;
use Carbon\Carbon;
use Auth;

class MasterDesaController extends Controller {

    use FlashMessageTraits;

    protected
        $PAGE_LIMIT,
        $PASS_REGEX

    ;

    function __construct() {
        $this->PASS_REGEX = config('setting.pass.regex');
    }

    function index(Request $request) {
        $data['cari'] = $request->get('cari');
        $data['desa'] = Desa::where('desa.status', 1)
            ->where(function ($query) use ($data) {
                $query->Where('desa.kecamatan', 'LIKE', '%' . $data['cari'] . '%')
                    ->orWhere('desa.desa', 'LIKE', '%' . $data['cari'] . '%')
                ->orWhere('desa.website', 'LIKE', '%' . $data['cari'] . '%')
                ->orWhere('desa.jenis', 'LIKE', '%' . $data['cari'] . '%');
            })

            ->orderBy('desa.created_at', 'desc')
            ->paginate(10);
//        print_r($data['desa']->links());die;
        return view('backend.master.desa', $data);
    }

    public function createView() {
        $data['desa'] = $this->desaList();
        return view('backend.master.modal.desa.create', $data);
    }
//
//    public function create(Request $request) {
//
//        $request->validate([
//            'kodedokter' => 'required|min:3|alpha_dash|unique:dokter,kodedokter',
//            'namalengkap' => 'required|min:3',
//            'idprofesi' => 'required|numeric',
//            'pendidikan' => 'required',
//            'foto' => 'mimes:jpg,jpeg,png',
//        ]);
//
//        DB::beginTransaction();
//        try {
//
//            $date = Carbon::now()->format('YmdHis');
//            $path = env('UPLOAD_PATH') . '/profile-dokter/';
//
//            $data = $request->all();
//
//            $data['foto'] = 'default.png';
//            if ($request->hasFile('foto')) {
//                $ext = $request->file('foto')->getClientOriginalExtension();
//                $filename = 'dokter' . Auth::id() .'_'. $date .'.'. $ext;
//                $request->file('foto')->move($path, $filename);
//                $data['foto'] = $filename;
//            }
//
//            unset($data['_token']);
//
//            Dokter::create($data);
//            DB::commit();
//
//            $this->message('success', 'Dokter berhasil ditambahkan.');
//            return redirect()->route('backend.master.dokterlist');
//        } catch (\Illuminate\Database\QueryException $ex) {
//            DB::rollback();
//            $debug = env('APP_DEBUG');
//            if ($debug) {
//                $message = $ex->getMessage();
//            } else {
//                $message = 'Ada yang salah saat menambahkan.';
//            }
//            $this->message('error', $message);
//            return redirect()->route('backend.master.dokterlist');
//        }
//    }
//
    public function desaList()
    {
        $desa = Desa::where('status', 1)->get();
        return $desa;
    }

    public function editView(Request $request) {
        $request->validate([
            'pid' => 'required|numeric',
        ]);

        $data['desa'] = Desa::find($request->pid);



        return view('backend.master.modal.desa.edit', $data);
    }
//
    public function edit(Request $request) {
        $rules = [
            'desa_id' => 'required|numeric',
        ];

        $this->validate($request, $rules);

        DB::beginTransaction();
        try {
            $data = [
                'website' => $request->website,
                'tte' => $request->tte,
                'jenis' => $request->jenis,
                'sosialisasi' => $request->sosialisasi,
            ];

            $desa = Desa::where('id', $request->desa_id)->update($data);

            DB::commit();

            $this->message('success', 'Desa berhasil dirubah.');
            return redirect()->route('backend.master.desalist');
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $debug = env('APP_DEBUG');
            if ($debug) {
                $message = $ex->getMessage();
            } else {
                $message = 'Ada yang salah saat memperbarui.';
            }
            $this->message('error', $message);
            return redirect()->route('backend.master.desalist');
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'uid' => 'required|numeric',
        ]);

        Dokter::where('id', $request->uid)
            ->update(['status' => 0]);

        $this->message('success', 'Dokter telah dihapus.');
        return redirect()->route('backend.master.dokterlist');
    }

}
