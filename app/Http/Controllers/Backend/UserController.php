<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Traits\FlashMessageTraits;
use App\Models\User;
use App\Models\Keluarga;
use App\Models\Param;

class UserController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT,
        $PASS_REGEX
    ;

    function __construct(
        UserRepositoryInterface $userRepo,
        RoleRepositoryInterface $roleRepo
    ) {
        $this->userRepo     = $userRepo;
        $this->roleRepo     = $roleRepo;
        $this->PASS_REGEX   = config('setting.pass.regex');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = $this->userRepo->pagination(10);

        return
            view('backend.user.index')
            ->with('users', $data->datas)
            ->with('status', $data->status)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
//        $data = $this->roleRepo->get();
//        dd($data);
        return
            view('backend.user.create')
            ->with('roles', $this->roleRepo->get())
        ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'role_id' => 'required',
            'name' => 'required|string|max:191',
            'telephone' => 'required|unique:users',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|min:8|confirmed',

        ]);

        //unset($data['_token']); 
        

        User::create($request->all());
        $parent_id = User::max('id');
        
        $xn1 = $this->params('kodekeluarga');
        $kodekeluarga = $xn1 + 1;
        $input = array(
            'kode_keluarga'             => $kodekeluarga,
            'parent_id'                 => $parent_id,
            'namalengkap'               => $request->name,
            'email'                     => $request->email,
            'status_dalam_keluarga'     => 'SINGLE',
            'notelpon'                  => $request->telephone,
            'alamat'                    => $request->address,
            'status'                    => 1,
            'created_by'                => $parent_id

        );

        Keluarga::create($input);
        $this->update_params($kodekeluarga);
        $this->message('success', 'User baru berhasil dibuat.');
        return redirect()->route('backend.user.index');
    }

    public function params($keterangan){
        $param = Param::where('keterangan',$keterangan)
                    ->first();
        return (isset($param->xn1) ? $param->xn1 : '');
    }

    public function parent_id(){
        return User::where('id')
                ->orderBy('id','desc')->first();
    }

    public function update_params($xn1){
        return Param::where('keterangan','kodekeluarga')
                ->update(['xn1'=>$xn1]);
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
        //
        return
            view('backend.user.edit')
            ->with('detail', $this->userRepo->find($id))
            ->with('roles', $this->roleRepo->get())
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
            'role_id' => 'required',
            'name' => 'required|string|max:191',
            'telephone' => 'required',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users,email,'.$id,
            'password' => 'string|min:8|confirmed|'.$this->PASS_REGEX,
        ]);
        
        $response = $this->userRepo->update($id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.user.index');
    }

    public function toggle(Request $request)
    {
        //
        $response = $this->userRepo->toggle($request);
        $this->message($response->level, $response->message);
        return back();
    }

    public function delete(Request $request){

        $request->validate([
            'uid' => 'required|numeric'
        ]);
       
        User::where('id', $request->uid)
                ->update(['status' => 0]);
        
        $this->message('success','User telah dihapus');
        return redirect()->route('backend.user.index');
    }

}
