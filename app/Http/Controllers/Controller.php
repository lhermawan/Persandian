<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Crypt;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function isAdmin($id)
    {
        if ($id == config('setting.isAdmin.key'))
        {
            return true;
        }

        return false;
    }

    protected function decryptingId($id)
    {
        return Crypt::decrypt($id);
    }

    protected function encryptingId($id)
    {
        return Crypt::encrypt($id);
    }

}
