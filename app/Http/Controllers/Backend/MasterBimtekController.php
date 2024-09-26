<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FlashMessageTraits;
use App\Models\Desa;
use App\Models\Bimtek;
use DB;
use Carbon\Carbon;
use Auth;

class MasterBimtekController extends Controller {

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
        $data['bimtek'] = Bimtek::where(function ($query) use ($data) {
                $query->Where('tgl_bimtek', 'LIKE', '%' . $data['cari'] . '%');

            })
            ->with('desa')
            ->orderBy('bimtek.created_at', 'desc')
            ->paginate(10);
        return view('backend.master.bimtek', $data);
    }

    public function createView(Request $request) {
//        $data['bimtek'] = $this->desaList();
//        $data['kecamatan'] = desaList::find($request->bimtek);

        $data['desa'] = Desa::select('id','kecamatan','desa')
            ->Orderby('kecamatan')
            ->get();
//        print_r($data['desa']);die;
        return view('backend.master.modal.bimtek.create', $data);
    }
//
    public function create(Request $request) {



        DB::beginTransaction();
        try {

            $date = Carbon::now()->format('YmdHis');
            $path = public_path().'/uploads/surat';

            $data = $request->all();


            if ($request->hasFile('surat')) {
                $ext = $request->file('surat')->getClientOriginalExtension();
                $filename = $data['id_desa'] .'_'. $date .'.'. $ext;
                $request->file('surat')->move($path, $filename);
                $data['surat'] = $filename;
            }

            $data['jenis_bimtek'] = $data['jenis_bimtek'];

            unset($data['_token']);
//            print_r($data);die;

            Bimtek::create($data);
            DB::commit();

            $this->message('success', 'Bimtek berhasil ditambahkan.');
            return redirect()->route('backend.master.bimteklist');
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $debug = env('APP_DEBUG');
            if ($debug) {
                $message = $ex->getMessage();
            } else {
                $message = 'Ada yang salah saat menambahkan.';
            }
            $this->message('error', $message);
            return redirect()->route('backend.master.bimteklist');
        }
    }

    public function bimtekList()
    {
        $bimtek = Bimtek::all();
        return $bimtek;
    }

    public function editView(Request $request) {
        $request->validate([
            'pid' => 'required|numeric',
        ]);

        $data['desa'] = Desa::find($request->pid);



        return view('backend.master.modal.bimtek.edit', $data);
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

            $this->message('success', 'Data Bimtek berhasil dirubah.');
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
            return redirect()->route('backend.master.bimteklist');
        }
    }

    public function delete(Request $request) {
        $request->validate([
            'uid' => 'required|numeric',
        ]);


        $d_bimtek = Bimtek::find($request->uid);
        unset($d_bimtek['surat']);
        $d_bimtek->delete();

        $this->message('success', ' Data Bimtek telah dihapus.');
        return redirect()->route('backend.master.bimteklist');
    }

}
