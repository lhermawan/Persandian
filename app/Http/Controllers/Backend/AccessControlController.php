<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\AccessControl\AccessControlRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Traits\FlashMessageTraits;

class AccessControlController extends Controller
{
    use FlashMessageTraits;

    // protected
    //     $PAGE_LIMIT,
    //     $SESSION_DATAUSER
    // ;

    function __construct(
        AccessControlRepositoryInterface $accessControlRepo,
        RoleRepositoryInterface $roleRepo
    )
    {
        $this->accessControlRepo = $accessControlRepo;
        $this->roleRepo = $roleRepo;
        //$this->PAGE_LIMIT = config('setting.pagination.limit');

        //No session access from constructor work arround
        $this->middleware(function ($request, $next)
        {
            $this->user = session('data')->user;
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
        $request = request();

        // If request id is unset
        (!isset($request->id)) ? $role_id = $this->user->role_id : $role_id = $this->decryptingId($request->id) ;

        $roles = $this->roleRepo->roles(config('setting.status.active'))->get();

        $access = $this->accessControlRepo->access($role_id);
        return
            view('backend.accesscontrol.index')
                ->with('roles', $roles)
                ->with('accesscontrols', $access)
            ;
    }


    public function update(Request $request)
    {
        $request->merge(['role' => $this->decryptingId($request->role) ]);
        $response = $this->accessControlRepo->update($request);
        $this->message($response->level, $response->message);
        return back();
    }
    // public function update(Request $request)
    //  {
    //      $response = $this->accessControlRepo->update($request);
    //      $this->message($response->level, $response->message);
    //      return redirect()->route('backend.accesscontrol.index');
    //  }

}
