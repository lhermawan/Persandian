<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\FlashMessageTraits;
use App\Models\Setting;
use DB;
use Carbon\Carbon;
use Auth;

class SettingController extends Controller {

    use FlashMessageTraits;

    protected
            $PAGE_LIMIT,
            $PASS_REGEX

    ;

    function __construct() {
        $this->PASS_REGEX = config('setting.pass.regex');
    }

    function index(Request $request) {
        $data['settings'] = Setting::where('status', 1)->orderBy('order', 'asc')->get();

        if($data['settings']->isEmpty()){
            return redirect()->route('backend.home');
        }

        return view('backend.setting.index', $data);
    }

    public function createView() {
        return view('backend.master.modal.dokter.create');
    }

    public function create(Request $request) {
        $request->validate([
            'kodedokter' => 'required|min:3|alpha_dash|unique:dokter,kodedokter',
            'namalengkap' => 'required|min:3',
            'profesi' => 'required',
            'pendidikan' => 'required',
            'foto' => 'required',
        ]);

        DB::beginTransaction();
        try {

//            $date = Carbon::now()->format('YmdHis');
//            $path = env('UPLOAD_PATH').'/profile-dokter';
//
//            if ($request->hasFile('foto')) {
//                $ext = $request->file('foto')->getClientOriginalExtension();
//                $filename = 'dokter' . Auth::id() . $date . $ext;
//                $request->file('foto')->move($path, $filename);
//                $data['foto'] = $filename;
//            }

            $data['foto'] = 'default.png';

            $data = $request->all();

            unset($data['_token']);

            Dokter::create($data);
            DB::commit();

            $this->message('success', 'Dokter berhasil ditambahkan.');
            return redirect()->route('backend.master.dokterlist');
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $debug = env('APP_DEBUG');
            if ($debug) {
                $message = $ex->getMessage();
            } else {
                $message = 'Ada yang salah saat menambahkan.';
            }
            $this->message('error', $message);
            return redirect()->route('backend.master.dokterlist');
        }
    }

    public function dokterList()
    {
        $dokter = Dokter::where('status',1)->orderBy('kodedokter', 'asc')->get();
        return $dokter;
    }

    public function poliList()
    {
        $poli = Poliklinik::where('status',1)->get();
        return $poli;
    }

    public function editView(Request $request) {
        $request->validate([
            'pid' => 'required|numeric',
        ]);

        $data['jadwal'] = Jadwalpraktek::find($request->pid);
        $data['dokter'] = $this->dokterList();
        $data['poliklinik'] = $this->poliList();

        if (!$data['jadwal']) {
            return "Tidak ada jadwal.";
        }

        return view('backend.master.modal.jadwal.edit', $data);
    }

    public function edit(Request $request) {

        $param = Setting::where('status', 1)->get();

        $rules = [];
        foreach ($param as $data) {
            $rules[$data['name']] = $data['validation'];
        }

        $this->validate($request, $rules);

        DB::beginTransaction();
        try {
            $data = $request->all();
            unset($data['_token']);

            foreach ($param as $setting) {
                foreach ($data as $key => $value) {
                    if($setting['name'] == $key){
                        Setting::where('name', $key)->update(['value' => $value, 'updated_by' => Auth::id()]);
                    }
                }
            }

            DB::commit();

            $this->message('success', 'Setting berhasil dirubah.');
            return redirect()->route('backend.setting.setting');
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $debug = env('APP_DEBUG');
            if ($debug) {
                $message = $ex->getMessage();
            } else {
                $message = 'Ada yang salah saat memperbarui.';
            }
            $this->message('error', $message);
            return redirect()->route('backend.setting.setting');
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
