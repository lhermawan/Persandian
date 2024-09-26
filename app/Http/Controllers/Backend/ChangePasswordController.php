<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\FlashMessageTraits;
use Auth;

class ChangePasswordController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT,
        $PASS_REGEX
    ;

    function __construct(
        UserRepositoryInterface $userRepo
    ) {
        $this->userRepo     = $userRepo;
        $this->PAGE_LIMIT   = config('setting.pagination.limit');
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
        return
            view('backend.change-password')
        ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        //
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed|'.$this->PASS_REGEX,
        ]);
        $id = Auth::user()->id;
        $response = $this->userRepo->changePassword($id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.change-password.index');
    }

    

}
