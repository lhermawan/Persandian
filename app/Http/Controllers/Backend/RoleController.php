<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Traits\FlashMessageTraits;

class RoleController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT
    ;

    function __construct(
        RoleRepositoryInterface $roleRepo
    ) {
        $this->roleRepo     = $roleRepo;
        $this->PAGE_LIMIT   = config('setting.pagination.limit');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = $this->roleRepo->pagination($this->PAGE_LIMIT);
        return
            view('backend.role.index')
            ->with('roles', $data->datas)
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
        return
            view('backend.role.create')
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
        $response = $this->roleRepo->store($request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.role.index');
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
            view('backend.role.edit')
            ->with('detail', $this->roleRepo->find($id))
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
        $response = $this->roleRepo->update($id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.role.index');
    }

    public function toggle(Request $request)
    {
        //
        $response = $this->roleRepo->toggle($request);
        $this->message($response->level, $response->message);
        return back();
    }

}
