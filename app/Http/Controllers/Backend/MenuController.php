<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Menu\MenuRepositoryInterface;
use App\Traits\FlashMessageTraits;
use App\Models\Menu;

class MenuController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT
    ;

    function __construct(
        MenuRepositoryInterface $menuRepo
    ) {
        $this->menuRepo     = $menuRepo;
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
        $data = $this->menuRepo->pagination($this->PAGE_LIMIT);
        return
            view('backend.menu.index')
                ->with('menus', $data->datas)
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
            view('backend.menu.create')
                ->with('parents', $this->menuRepo->parents()->get())
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
        $response = $this->menuRepo->store($request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.menu.index');
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
            view('backend.menu.edit')
                ->with('detail', $this->menuRepo->find($id))
                ->with('parents', $this->menuRepo->parents()->get())
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
        $response = $this->menuRepo->update($id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.menu.index');
    }

    public function toggle(Request $request)
    {
        //
        $response = $this->menuRepo->toggle($request);
        $this->message($response->level, $response->message);
        return back();
    }

    public function delete(Request $request){
        $update = array(
            'status' => 0
        );
        $response = Menu::where('id',$request->uid)->update($update);
        $this->message('success','Menu berhasil di nonaktifkan');
        return redirect()->route('backend.menu.index');
    }

}
