<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Company\CompanyRepositoryInterface;
use App\Traits\FlashMessageTraits;

class CompanyController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT
    ;

    function __construct(
        CompanyRepositoryInterface $companyRepo
    ) {
        $this->companyRepo  = $companyRepo;
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
        $data = $this->companyRepo->pagination($this->PAGE_LIMIT);
        return
            view('backend.company.index')
            ->with('companies', $data->datas)
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
            view('backend.company.create')
            ->with('parents', $this->companyRepo->parents()->get())
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
        $response = $this->companyRepo->store($request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.company.index');
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
            view('backend.company.edit')
            ->with('detail', $this->companyRepo->find($id))
            ->with('parents', $this->companyRepo->parents()->get())
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
        $response = $this->companyRepo->update($id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.company.index');
    }

    public function toggle(Request $request)
    {
        //
        $response = $this->companyRepo->toggle($request);
        $this->message($response->level, $response->message);
        return back();
    }

}
