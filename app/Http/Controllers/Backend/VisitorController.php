<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Visitor\VisitorRepositoryInterface;
use App\Traits\FlashMessageTraits;

class VisitorController extends Controller
{
    use FlashMessageTraits;

    protected
        $PAGE_LIMIT,
        $PASS_REGEX
    ;

    function __construct(
        VisitorRepositoryInterface $visitorRepo
    ) {
        $this->visitorRepo     = $visitorRepo;
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
            view('backend.visitor.add');
    }

    

}
