<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Bill\BillRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Pdam\PdamRepositoryInterface;

use App\Traits\FlashMessageTraits;

class BillController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT,
        $SESSION_DATAUSER
    ;

    function __construct(
        CustomerRepositoryInterface $customerRepo,
        PdamRepositoryInterface $pdamRepo
    ) {
        $this->customerRepo = $customerRepo;
        $this->pdamRepo = $pdamRepo;
        $this->PAGE_LIMIT   = config('setting.pagination.limit');

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
    public function index()
    {
        //
        $id = $this->SESSION_DATAUSER->user->id;
        $data = $this->customerRepo->pagination($this->PAGE_LIMIT, $id);
        return
            view('backend.bill.index')
            ->with('customers', $data->datas)
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
            view('backend.customer.create')
            ->with('pdams', $this->pdamRepo->get())
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
        $request->user_id = $this->SESSION_DATAUSER->user->id;
        $response = $this->customerRepo->store($request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.customer.index');
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
            view('backend.customer.edit')
            ->with('pdams', $this->pdamRepo->get())
            ->with('detail', $this->customerRepo->find($id))
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
        $response = $this->customerRepo->update($id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.customer.index');
    }

    public function toggle(Request $request)
    {
        //
        $response = $this->customerRepo->toggle($request);
        $this->message($response->level, $response->message);
        return back();
    }

}
