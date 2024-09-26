<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use App\Traits\FlashMessageTraits;
use Auth;

class ProfileController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT
    ;

    function __construct(
        UserRepositoryInterface $userRepo
    ) {
        $this->userRepo     = $userRepo;
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
        return
            view('backend.profile')
            ->with('user', Auth::user())
        ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:191',
            'address' => 'required|string',
            'about' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $response = $this->userRepo->updateProfile(Auth::user()->id, $request);
        $this->message($response->level, $response->message);
        return redirect()->route('backend.profile.index');
    }

    

}
