<?php

namespace App\Repositories\AccessControl;
use App\Repositories\Base\BaseRepository;

interface AccessControlRepositoryInterface
{
    function update($attributes);
    function access($id);

}