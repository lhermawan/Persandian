<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Berita;
use Carbon\Carbon;
use File;



use App\Traits\FlashMessageTraits;

class BeritaController extends Controller
{
    use FlashMessageTraits;

    protected $SESSION_DATAUSER;

    function __construct(
        
    ) {
        

        //No session access from constructor work arround
        $this->middleware(function ($request, $next)
        {
            $this->SESSION_DATAUSER = session('data');
            return $next($request);
        });
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        //

        $data = Berita::where( ['status' => '1'] )
                ->orderBy('created_at','desc')
                ->paginate(10);
        $path = env('API_PATH') . 'booking-online.morfem.id/uploads/berita/';
        
        
        return
            view('backend.berita.index')
                ->with('berita',$data)
                ->with('path',$path);
            
           
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       return view('backend.berita.create');     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'kode_news' => 'required|unique:berita,kode_news',
            'judul' => 'required',
            'isi' => 'required',
            'url' => 'required',
            
        ]);

        $date = Carbon::now()->format('YmdHis');
         $path = public_path().'/uploads/berita';

         
        if ($request->hasFile('gambar')) {
               $ext = $request->file('gambar')->getClientOriginalExtension();
               $filename = 'gambar' . $date .'.'. $ext;
               $request->file('gambar')->move($path, $filename);
               
               $input = array(
                    'kode_news'=> $request->kode_news,
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'gambar' =>$filename,
                    'url' =>$request->url,
                    'status' => 1
               );
               
               Berita::create($input);

               $this->message('success', 'Berita berhasil ditambahkan.');
                return redirect()->route('backend.berita.index');               
           }else{
                $this->message('error', 'Berita gagal untuk disimpan.');
                return redirect()->route('backend.berita.create');
           }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data['path']   = public_path();
        $data['berita'] = Berita::find($id);
        return
            view('backend.berita.edit', $data)
        ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'url' => 'required',          
        ]);


        if($request->hasFile('gambar')){
        
               $path = public_path().'/uploads/berita';                
               $gambar = Berita::where('id',$id)->first();
               File::delete($path.'/'.$gambar->gambar);

               $date = Carbon::now()->format('YmdHis');
               $ext = $request->file('gambar')->getClientOriginalExtension();
               $filename = 'gambar' . $date .'.'. $ext;

               $input = array(
                    'kode_news'=> $request->kode_news,
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'gambar' =>$filename,
                    'url' =>$request->url,
                    
               );


               $request->file('gambar')->move($path, $filename);
               
               
               Berita::where('id',$id)->update($input);

               $this->message('success', 'Berita berhasil diupdate.');
                return redirect()->route('backend.berita.index');      
        }else{

              $input = array(
                    'judul' => $request->judul,
                    'isi' => $request->isi,
                    'url' =>$request->url           
               );
                      
            Berita::where('id',$id)->update($input);

            $this->message('success', 'Berita berhasil diupdate.');
            return redirect()->route('backend.berita.index');
        }

    }

    public function delete(Request $request){
        $request->validate(
            ['uid' => 'required']
        );

        Berita::where('id',$request->uid)->update(['status'=>0]);
        
        $this->message('success','Berita telah dihapus');
        return redirect()->route('backend.berita.index');
    }

}
